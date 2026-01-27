<?php

namespace App\Jobs;

use App\Models\Form;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [10, 60, 300];

    public function __construct(
        public Form $form,
        public Submission $submission
    ) {}

    public function handle(): void
    {
        if (!$this->form->webhook_enabled || !$this->form->webhook_url) {
            return;
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Formet-Webhook/1.0',
                    'X-Formet-Form-ID' => $this->form->id,
                    'X-Formet-Submission-ID' => $this->submission->id,
                ])
                ->post($this->form->webhook_url, $this->submission->toWebhookPayload());

            if (!$response->successful()) {
                Log::warning('Webhook failed', [
                    'form_id' => $this->form->id,
                    'submission_id' => $this->submission->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Webhook error', [
                'form_id' => $this->form->id,
                'submission_id' => $this->submission->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
