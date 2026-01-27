<?php

namespace App\Services;

use App\Models\Form;
use Illuminate\Support\Facades\Http;

class CaptchaService
{
    public function verify(Form $form, ?string $token): bool
    {
        if ($form->captcha_type === 'none' || !$token) {
            return $form->captcha_type === 'none';
        }

        return match ($form->captcha_type) {
            'recaptcha_v3' => $this->verifyRecaptcha($form, $token),
            'hcaptcha' => $this->verifyHcaptcha($form, $token),
            default => true,
        };
    }

    protected function verifyRecaptcha(Form $form, string $token): bool
    {
        if (!$form->captcha_secret_key) {
            return true;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $form->captcha_secret_key,
                'response' => $token,
            ]);

            $data = $response->json();

            // For reCAPTCHA v3, check score (0.5 is a reasonable threshold)
            if (isset($data['score'])) {
                return $data['success'] && $data['score'] >= 0.5;
            }

            return $data['success'] ?? false;
        } catch (\Exception $e) {
            // If verification fails, allow submission but log the error
            logger()->error('reCAPTCHA verification failed', ['error' => $e->getMessage()]);
            return true;
        }
    }

    protected function verifyHcaptcha(Form $form, string $token): bool
    {
        if (!$form->captcha_secret_key) {
            return true;
        }

        try {
            $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
                'secret' => $form->captcha_secret_key,
                'response' => $token,
            ]);

            $data = $response->json();
            return $data['success'] ?? false;
        } catch (\Exception $e) {
            logger()->error('hCaptcha verification failed', ['error' => $e->getMessage()]);
            return true;
        }
    }
}
