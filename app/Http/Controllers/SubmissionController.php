<?php

namespace App\Http\Controllers;

use App\Jobs\SendAutoresponder;
use App\Jobs\SendDiscordNotification;
use App\Jobs\SendEmailNotification;
use App\Jobs\SendSlackNotification;
use App\Jobs\SendWebhook;
use App\Models\Form;
use App\Models\Submission;
use App\Services\CaptchaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function __construct(
        protected CaptchaService $captchaService
    ) {}

    /**
     * Handle incoming form submission (public endpoint).
     * Supports both regular endpoints and email-as-endpoint (e.g., /f/you@example.com)
     */
    public function store(Request $request, string $endpoint): Response|RedirectResponse|JsonResponse
    {
        $form = $this->resolveForm($endpoint);

        if (!$form->is_active) {
            return $this->errorResponse($request, 'This form is not accepting submissions.', 403);
        }

        // Check domain restriction
        $origin = $request->header('Origin') ?? $request->header('Referer');
        if (!$form->isOriginAllowed($origin)) {
            return $this->errorResponse($request, 'Submissions from this domain are not allowed.', 403);
        }

        // Rate limiting
        $rateLimitKey = $form->getRateLimitKey() . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, $form->rate_limit_per_minute ?? 10)) {
            return $this->errorResponse($request, 'Too many submissions. Please try again later.', 429);
        }
        RateLimiter::hit($rateLimitKey, 60);

        // CAPTCHA verification
        $captchaToken = $request->input('g-recaptcha-response') ?? $request->input('h-captcha-response') ?? $request->input('_captcha_token');
        if (!$this->captchaService->verify($form, $captchaToken)) {
            return $this->errorResponse($request, 'CAPTCHA verification failed.', 400);
        }

        // Get all form data except internal fields
        $excludeFields = ['_token', '_method', '_honeypot', 'g-recaptcha-response', 'h-captcha-response', '_captcha_token'];
        $data = $request->except($excludeFields);

        // Handle file uploads
        $files = [];
        if ($form->file_uploads_enabled) {
            $files = $this->handleFileUploads($request, $form);
            // Remove file inputs from data array
            foreach ($request->allFiles() as $key => $file) {
                unset($data[$key]);
            }
        }

        // Reject empty submissions
        if (empty($data) && empty($files)) {
            return $this->errorResponse($request, 'No form data received.', 400);
        }

        // Honeypot spam check
        $isSpam = $request->filled('_honeypot');

        // Create submission
        $submission = $form->submissions()->create([
            'data' => $data,
            'files' => $files ?: null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'is_spam' => $isSpam,
        ]);

        // Process notifications and integrations (only if not spam)
        if (!$isSpam) {
            $this->processNotifications($form, $submission);
        }

        // Handle response based on request type
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Form submitted successfully',
                'submission_id' => $submission->id,
            ]);
        }

        if ($form->redirect_url) {
            return redirect()->away($form->redirect_url);
        }

        return response()->view('submissions.thanks', compact('form'));
    }

    /**
     * Handle file uploads.
     */
    protected function handleFileUploads(Request $request, Form $form): array
    {
        $files = [];
        $maxSize = ($form->max_file_size_mb ?? 10) * 1024; // Convert MB to KB for validation
        $allowedTypes = $form->getAllowedFileTypes();

        // Map extensions to allowed MIME types for security
        $mimeTypeMap = [
            'jpg' => ['image/jpeg', 'image/jpg'],
            'jpeg' => ['image/jpeg', 'image/jpg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'xls' => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'txt' => ['text/plain'],
            'csv' => ['text/csv', 'text/plain', 'application/csv'],
            'zip' => ['application/zip', 'application/x-zip-compressed'],
        ];

        foreach ($request->allFiles() as $key => $uploadedFiles) {
            // Handle both single files and arrays of files
            $uploadedFiles = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];

            foreach ($uploadedFiles as $file) {
                if (!$file->isValid()) {
                    continue;
                }

                // Check file size (in KB)
                if ($file->getSize() / 1024 > $maxSize) {
                    continue;
                }

                // Check file extension
                $extension = strtolower($file->getClientOriginalExtension());
                if (!in_array($extension, $allowedTypes)) {
                    continue;
                }

                // Validate MIME type matches extension for security
                $mimeType = $file->getMimeType();
                if (isset($mimeTypeMap[$extension])) {
                    if (!in_array($mimeType, $mimeTypeMap[$extension])) {
                        // MIME type doesn't match extension - potential security issue
                        continue;
                    }
                }

                // Store the file
                $path = $file->store("submissions/{$form->id}", 'public');

                $files[] = [
                    'field' => $key,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $mimeType,
                ];
            }
        }

        return $files;
    }

    /**
     * Process all notifications and integrations.
     * All notifications are dispatched to the queue for reliability.
     */
    protected function processNotifications(Form $form, Submission $submission): void
    {
        // Email notification to form owner (queued)
        if ($form->email_notifications) {
            SendEmailNotification::dispatch($form, $submission);
        }

        // Autoresponder to submitter (queued)
        if ($form->autoresponder_enabled) {
            SendAutoresponder::dispatch($form, $submission);
        }

        // Webhook (queued)
        if ($form->webhook_enabled && $form->webhook_url) {
            SendWebhook::dispatch($form, $submission);
        }

        // Slack (queued)
        if ($form->slack_enabled && $form->slack_webhook_url) {
            SendSlackNotification::dispatch($form, $submission);
        }

        // Discord (queued)
        if ($form->discord_enabled && $form->discord_webhook_url) {
            SendDiscordNotification::dispatch($form, $submission);
        }
    }

    /**
     * View a single submission.
     */
    public function show(Form $form, Submission $submission): View
    {
        $this->authorize('view', $form);

        if ($submission->form_id !== $form->id) {
            abort(404);
        }

        // Mark as read
        if (!$submission->is_read) {
            $submission->update(['is_read' => true]);
        }

        return view('submissions.show', compact('form', 'submission'));
    }

    /**
     * Delete a submission.
     */
    public function destroy(Form $form, Submission $submission): RedirectResponse
    {
        $this->authorize('delete', $form);

        if ($submission->form_id !== $form->id) {
            abort(404);
        }

        // Delete associated files
        if ($submission->files) {
            foreach ($submission->files as $file) {
                Storage::disk('public')->delete($file['path']);
            }
        }

        $submission->delete();

        return redirect()->route('forms.show', $form)
            ->with('status', 'Submission deleted.');
    }

    /**
     * Mark submission as spam.
     */
    public function markSpam(Form $form, Submission $submission): RedirectResponse
    {
        $this->authorize('update', $form);

        if ($submission->form_id !== $form->id) {
            abort(404);
        }

        $submission->update(['is_spam' => true]);

        return back()->with('status', 'Marked as spam.');
    }

    /**
     * Export submissions as CSV.
     */
    public function export(Form $form, Request $request): Response
    {
        $this->authorize('view', $form);

        $format = $request->query('format', 'csv');

        $submissions = $form->submissions()
            ->where('is_spam', false)
            ->latest()
            ->get()
            ->filter(fn($s) => !empty($s->data));

        if ($submissions->isEmpty()) {
            return response("No submissions to export.\n")
                ->header('Content-Type', 'text/plain');
        }

        if ($format === 'json') {
            return $this->exportJson($form, $submissions);
        }

        return $this->exportCsv($form, $submissions);
    }

    /**
     * Export as CSV.
     */
    protected function exportCsv(Form $form, $submissions): Response
    {
        // Get all unique field names
        $fields = collect();
        foreach ($submissions as $sub) {
            $fields = $fields->merge(array_keys($sub->data));
        }
        $fields = $fields->unique()->values();

        // Add metadata columns
        $fields = $fields->push('_submitted_at', '_ip_address');

        // Build CSV
        $csv = implode(',', $fields->map(fn($f) => '"' . str_replace('"', '""', $f) . '"')->toArray()) . "\n";

        foreach ($submissions as $sub) {
            $row = $fields->map(function ($f) use ($sub) {
                if ($f === '_submitted_at') {
                    return '"' . $sub->created_at->toIso8601String() . '"';
                }
                if ($f === '_ip_address') {
                    return '"' . ($sub->ip_address ?? '') . '"';
                }
                $value = $sub->data[$f] ?? '';
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                return '"' . str_replace('"', '""', $value) . '"';
            });
            $csv .= implode(',', $row->toArray()) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $form->name . '-submissions.csv"');
    }

    /**
     * Export as JSON.
     */
    protected function exportJson(Form $form, $submissions): Response
    {
        $data = $submissions->map(function ($sub) {
            return [
                'id' => $sub->id,
                'data' => $sub->data,
                'files' => $sub->getFileUrls(),
                'submitted_at' => $sub->created_at->toIso8601String(),
                'ip_address' => $sub->ip_address,
                'referrer' => $sub->referrer,
            ];
        })->values();

        return response(json_encode([
            'form' => [
                'id' => $form->id,
                'name' => $form->name,
            ],
            'submissions' => $data,
            'exported_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT))
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $form->name . '-submissions.json"');
    }

    protected function errorResponse(Request $request, string $message, int $status): Response|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $message], $status);
        }

        abort($status, $message);
    }

    /**
     * Resolve form from endpoint - supports both regular endpoints and email addresses.
     * Email endpoints auto-create forms: /f/you@example.com just works.
     */
    protected function resolveForm(string $endpoint): Form
    {
        // Check if endpoint is an email address
        if (filter_var($endpoint, FILTER_VALIDATE_EMAIL)) {
            return Form::firstOrCreate(
                ['endpoint' => strtolower($endpoint)],
                [
                    'name' => 'Form for ' . $endpoint,
                    'email_notifications' => true,
                    'notification_email' => strtolower($endpoint),
                    'user_id' => null,
                    'is_active' => true,
                    'captcha_type' => 'none',
                ]
            );
        }

        return Form::where('endpoint', $endpoint)->firstOrFail();
    }
}
