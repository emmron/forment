<?php

namespace App\Http\Middleware;

use App\Models\Form;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->bearerToken() ?? $request->header('X-API-Key');

        if (!$apiKey) {
            return response()->json([
                'error' => 'API key is required.',
                'message' => 'Please provide your API key via Bearer token or X-API-Key header.',
            ], 401);
        }

        $form = Form::where('api_key', $apiKey)->first();

        if (!$form) {
            return response()->json([
                'error' => 'Invalid API key.',
            ], 401);
        }

        // Attach the form to the request for use in controllers
        $request->attributes->set('form', $form);

        return $next($request);
    }
}
