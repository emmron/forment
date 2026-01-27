<?php

use App\Http\Controllers\Api\FormApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes require authentication via API key.
| Provide your API key via:
| - Authorization: Bearer YOUR_API_KEY
| - X-API-Key: YOUR_API_KEY
|
*/

Route::middleware('api.key')->group(function () {
    // Form information
    Route::get('/form', [FormApiController::class, 'index']);
    Route::get('/form/stats', [FormApiController::class, 'stats']);

    // Submissions
    Route::get('/submissions', [FormApiController::class, 'submissions']);
    Route::get('/submissions/{submission}', [FormApiController::class, 'submission']);
    Route::delete('/submissions/{submission}', [FormApiController::class, 'deleteSubmission']);
    Route::post('/submissions/{submission}/spam', [FormApiController::class, 'markSpam']);
    Route::delete('/submissions/{submission}/spam', [FormApiController::class, 'unmarkSpam']);
});
