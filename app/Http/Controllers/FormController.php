<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormController extends Controller
{
    public function index(): View
    {
        $forms = auth()->user()->forms()->withCount('submissions')->latest()->get();
        return view('forms.index', compact('forms'));
    }

    public function create(): View
    {
        return view('forms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email_notifications' => ['boolean'],
            'redirect_url' => ['nullable', 'url', 'max:500'],
        ]);

        $form = auth()->user()->forms()->create([
            'name' => $validated['name'],
            'email_notifications' => $request->boolean('email_notifications', true),
            'redirect_url' => $validated['redirect_url'] ?? null,
        ]);

        return redirect()->route('forms.show', $form)
            ->with('status', 'Form endpoint created successfully!');
    }

    public function show(Form $form): View
    {
        $this->authorize('view', $form);

        $submissions = $form->submissions()
            ->where('is_spam', false)
            ->latest()
            ->paginate(20);

        // Get stats for the form
        $stats = [
            'total' => $form->submissions()->count(),
            'spam' => $form->submissions()->where('is_spam', true)->count(),
            'unread' => $form->submissions()->where('is_read', false)->where('is_spam', false)->count(),
            'today' => $form->submissions()->where('is_spam', false)->whereDate('created_at', today())->count(),
            'this_week' => $form->submissions()->where('is_spam', false)->where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        return view('forms.show', compact('form', 'submissions', 'stats'));
    }

    public function edit(Form $form): View
    {
        $this->authorize('update', $form);
        return view('forms.edit', compact('form'));
    }

    public function update(Request $request, Form $form): RedirectResponse
    {
        $this->authorize('update', $form);

        $validated = $request->validate([
            // Basic settings
            'name' => ['required', 'string', 'max:255'],
            'redirect_url' => ['nullable', 'url', 'max:500'],
            'is_active' => ['boolean'],

            // Email notifications
            'email_notifications' => ['boolean'],
            'notification_email' => ['nullable', 'email', 'max:255'],

            // Autoresponder
            'autoresponder_enabled' => ['boolean'],
            'autoresponder_subject' => ['nullable', 'string', 'max:255'],
            'autoresponder_message' => ['nullable', 'string', 'max:5000'],
            'autoresponder_from_name' => ['nullable', 'string', 'max:255'],
            'autoresponder_reply_to' => ['nullable', 'email', 'max:255'],

            // Webhook
            'webhook_enabled' => ['boolean'],
            'webhook_url' => ['nullable', 'url', 'max:500'],

            // Slack
            'slack_enabled' => ['boolean'],
            'slack_webhook_url' => ['nullable', 'url', 'max:500'],

            // Discord
            'discord_enabled' => ['boolean'],
            'discord_webhook_url' => ['nullable', 'url', 'max:500'],

            // CAPTCHA
            'captcha_type' => ['nullable', 'in:none,recaptcha_v3,hcaptcha'],
            'captcha_site_key' => ['nullable', 'string', 'max:255'],
            'captcha_secret_key' => ['nullable', 'string', 'max:255'],

            // File uploads
            'file_uploads_enabled' => ['boolean'],
            'max_file_size_mb' => ['nullable', 'integer', 'min:1', 'max:25'],
            'allowed_file_types' => ['nullable', 'string', 'max:500'],

            // Custom SMTP
            'custom_smtp_enabled' => ['boolean'],
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'smtp_encryption' => ['nullable', 'in:tls,ssl,none'],
            'smtp_from_email' => ['nullable', 'email', 'max:255'],
            'smtp_from_name' => ['nullable', 'string', 'max:255'],

            // Security
            'allowed_domains' => ['nullable', 'string', 'max:1000'],
            'rate_limit_per_minute' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ]);

        // Process allowed_domains from comma-separated string to array
        $allowedDomains = null;
        if (!empty($validated['allowed_domains'])) {
            $allowedDomains = array_map('trim', explode(',', $validated['allowed_domains']));
            $allowedDomains = array_filter($allowedDomains);
        }

        // Process allowed_file_types from comma-separated string to array
        $allowedFileTypes = null;
        if (!empty($validated['allowed_file_types'])) {
            $allowedFileTypes = array_map('trim', explode(',', $validated['allowed_file_types']));
            $allowedFileTypes = array_filter($allowedFileTypes);
            $allowedFileTypes = array_map('strtolower', $allowedFileTypes);
        }

        $form->update([
            'name' => $validated['name'],
            'redirect_url' => $validated['redirect_url'] ?? null,
            'is_active' => $request->boolean('is_active', true),

            'email_notifications' => $request->boolean('email_notifications'),
            'notification_email' => $validated['notification_email'] ?? null,

            'autoresponder_enabled' => $request->boolean('autoresponder_enabled'),
            'autoresponder_subject' => $validated['autoresponder_subject'] ?? null,
            'autoresponder_message' => $validated['autoresponder_message'] ?? null,
            'autoresponder_from_name' => $validated['autoresponder_from_name'] ?? null,
            'autoresponder_reply_to' => $validated['autoresponder_reply_to'] ?? null,

            'webhook_enabled' => $request->boolean('webhook_enabled'),
            'webhook_url' => $validated['webhook_url'] ?? null,

            'slack_enabled' => $request->boolean('slack_enabled'),
            'slack_webhook_url' => $validated['slack_webhook_url'] ?? null,

            'discord_enabled' => $request->boolean('discord_enabled'),
            'discord_webhook_url' => $validated['discord_webhook_url'] ?? null,

            'captcha_type' => $validated['captcha_type'] ?? 'none',
            'captcha_site_key' => $validated['captcha_site_key'] ?? null,
            'captcha_secret_key' => $validated['captcha_secret_key'] ?? null,

            'file_uploads_enabled' => $request->boolean('file_uploads_enabled'),
            'max_file_size_mb' => $validated['max_file_size_mb'] ?? 10,
            'allowed_file_types' => $allowedFileTypes,

            'custom_smtp_enabled' => $request->boolean('custom_smtp_enabled'),
            'smtp_host' => $validated['smtp_host'] ?? null,
            'smtp_port' => $validated['smtp_port'] ?? null,
            'smtp_username' => $validated['smtp_username'] ?? null,
            'smtp_password' => $validated['smtp_password'] ?? null,
            'smtp_encryption' => $validated['smtp_encryption'] ?? null,
            'smtp_from_email' => $validated['smtp_from_email'] ?? null,
            'smtp_from_name' => $validated['smtp_from_name'] ?? null,

            'allowed_domains' => $allowedDomains,
            'rate_limit_per_minute' => $validated['rate_limit_per_minute'] ?? 10,
        ]);

        return redirect()->route('forms.show', $form)
            ->with('status', 'Form settings updated!');
    }

    public function destroy(Form $form): RedirectResponse
    {
        $this->authorize('delete', $form);

        $form->delete();

        return redirect()->route('forms.index')
            ->with('status', 'Form deleted successfully.');
    }

    /**
     * Generate or regenerate API key for a form.
     */
    public function generateApiKey(Form $form): RedirectResponse
    {
        $this->authorize('update', $form);

        $form->generateApiKey();

        return back()->with('status', 'API key generated successfully. Make sure to copy it now - it won\'t be shown again.');
    }
}
