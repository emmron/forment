<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $forms = $user->forms()->withCount('submissions')->latest()->get();
        $formIds = $forms->pluck('id');

        // Core stats
        $totalSubmissions = $forms->sum('submissions_count');
        $unreadCount = Submission::whereIn('form_id', $formIds)
            ->where('is_spam', false)
            ->where('is_read', false)
            ->count();
        $todayCount = Submission::whereIn('form_id', $formIds)
            ->where('is_spam', false)
            ->whereDate('created_at', today())
            ->count();
        $weekCount = Submission::whereIn('form_id', $formIds)
            ->where('is_spam', false)
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();
        $spamCount = Submission::whereIn('form_id', $formIds)
            ->where('is_spam', true)
            ->count();

        // Recent submissions
        $recentSubmissions = Submission::whereIn('form_id', $formIds)
            ->where('is_spam', false)
            ->with('form')
            ->latest()
            ->take(10)
            ->get();

        // Chart data - last 14 days
        $chartData = Submission::whereIn('form_id', $formIds)
            ->where('is_spam', false)
            ->where('created_at', '>=', now()->subDays(14))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Fill in missing days
        $chartLabels = [];
        $chartValues = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M j');
            $chartValues[] = $chartData[$date] ?? 0;
        }

        return view('dashboard.index', compact(
            'forms',
            'totalSubmissions',
            'unreadCount',
            'todayCount',
            'weekCount',
            'spamCount',
            'recentSubmissions',
            'chartLabels',
            'chartValues'
        ));
    }
}
