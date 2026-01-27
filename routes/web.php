<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

// Landing page - auto-login as demo user
Route::get('/', function () {
    if (!auth()->check()) {
        // Auto-login as demo user (guest mode)
        $demoUser = \App\Models\User::firstOrCreate(
            ['email' => 'demo@formet.io'],
            [
                'name' => 'Demo User',
                'password' => bcrypt('password')
            ]
        );
        auth()->login($demoUser);
    }
    return redirect()->route('dashboard');
})->name('home');

// Guest routes - redirect to home (which auto-logs in)
Route::middleware('guest')->group(function () {
    Route::get('register', fn() => redirect()->route('home'))->name('register');
    Route::get('login', fn() => redirect()->route('home'))->name('login');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Forms CRUD
    Route::resource('forms', FormController::class);

    // API Key generation
    Route::post('forms/{form}/api-key', [FormController::class, 'generateApiKey'])->name('forms.api-key');

    // Submissions
    Route::get('forms/{form}/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
    Route::delete('forms/{form}/submissions/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
    Route::post('forms/{form}/submissions/{submission}/spam', [SubmissionController::class, 'markSpam'])->name('submissions.spam');
    Route::get('forms/{form}/export', [SubmissionController::class, 'export'])->name('forms.export');
});

// Public form submission endpoint (no auth required)
Route::post('f/{endpoint}', [SubmissionController::class, 'store'])->name('submit');
