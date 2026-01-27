<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormApiController extends Controller
{
    /**
     * List all forms for the authenticated API key.
     */
    public function index(Request $request): JsonResponse
    {
        $form = $request->attributes->get('form');

        // Return only this form's data (API key is per-form)
        return response()->json([
            'form' => [
                'id' => $form->id,
                'name' => $form->name,
                'endpoint' => $form->endpoint,
                'endpoint_url' => $form->endpoint_url,
                'is_active' => $form->is_active,
                'submissions_count' => $form->submissions()->count(),
                'created_at' => $form->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * List submissions for the form.
     */
    public function submissions(Request $request): JsonResponse
    {
        $form = $request->attributes->get('form');

        $query = $form->submissions();

        // Filter by spam status
        if ($request->has('spam')) {
            $query->where('is_spam', $request->boolean('spam'));
        } else {
            $query->where('is_spam', false);
        }

        // Filter by read status
        if ($request->has('read')) {
            $query->where('is_read', $request->boolean('read'));
        }

        // Date filters
        if ($request->has('since')) {
            $query->where('created_at', '>=', $request->input('since'));
        }

        if ($request->has('until')) {
            $query->where('created_at', '<=', $request->input('until'));
        }

        // Pagination
        $perPage = min($request->integer('per_page', 50), 100);
        $submissions = $query->latest()->paginate($perPage);

        return response()->json([
            'submissions' => $submissions->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'data' => $sub->data,
                    'files' => $sub->getFileUrls(),
                    'is_spam' => $sub->is_spam,
                    'is_read' => $sub->is_read,
                    'ip_address' => $sub->ip_address,
                    'referrer' => $sub->referrer,
                    'created_at' => $sub->created_at->toIso8601String(),
                ];
            }),
            'pagination' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'per_page' => $submissions->perPage(),
                'total' => $submissions->total(),
            ],
        ]);
    }

    /**
     * Get a single submission.
     */
    public function submission(Request $request, int $submissionId): JsonResponse
    {
        $form = $request->attributes->get('form');
        $submission = $form->submissions()->findOrFail($submissionId);

        return response()->json([
            'submission' => [
                'id' => $submission->id,
                'data' => $submission->data,
                'files' => $submission->getFileUrls(),
                'is_spam' => $submission->is_spam,
                'is_read' => $submission->is_read,
                'ip_address' => $submission->ip_address,
                'user_agent' => $submission->user_agent,
                'referrer' => $submission->referrer,
                'created_at' => $submission->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Delete a submission.
     */
    public function deleteSubmission(Request $request, int $submissionId): JsonResponse
    {
        $form = $request->attributes->get('form');
        $submission = $form->submissions()->findOrFail($submissionId);
        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Submission deleted.',
        ]);
    }

    /**
     * Mark submission as spam.
     */
    public function markSpam(Request $request, int $submissionId): JsonResponse
    {
        $form = $request->attributes->get('form');
        $submission = $form->submissions()->findOrFail($submissionId);
        $submission->update(['is_spam' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Submission marked as spam.',
        ]);
    }

    /**
     * Mark submission as not spam.
     */
    public function unmarkSpam(Request $request, int $submissionId): JsonResponse
    {
        $form = $request->attributes->get('form');
        $submission = $form->submissions()->findOrFail($submissionId);
        $submission->update(['is_spam' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Submission unmarked as spam.',
        ]);
    }

    /**
     * Get form statistics.
     */
    public function stats(Request $request): JsonResponse
    {
        $form = $request->attributes->get('form');

        $total = $form->submissions()->count();
        $spam = $form->submissions()->where('is_spam', true)->count();
        $unread = $form->submissions()->where('is_read', false)->where('is_spam', false)->count();

        // Submissions over time (last 30 days)
        $daily = $form->submissions()
            ->where('is_spam', false)
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        return response()->json([
            'stats' => [
                'total_submissions' => $total,
                'spam_submissions' => $spam,
                'unread_submissions' => $unread,
                'daily_submissions' => $daily,
            ],
        ]);
    }
}
