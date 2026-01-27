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

class SendDiscordNotification implements ShouldQueue
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
        if (!$this->form->discord_enabled || !$this->form->discord_webhook_url) {
            return;
        }

        $fields = [];
        foreach ($this->submission->data as $key => $value) {
            $displayValue = is_array($value) ? json_encode($value) : (string)$value;
            // Discord has a limit of 25 fields and 1024 chars per field value
            if (count($fields) < 25) {
                $fields[] = [
                    'name' => substr($key, 0, 256),
                    'value' => substr($displayValue, 0, 1024) ?: '(empty)',
                    'inline' => strlen($displayValue) < 50,
                ];
            }
        }

        $embed = [
            'title' => "New submission: {$this->form->name}",
            'color' => 0x6366f1, // Indigo color
            'fields' => $fields,
            'footer' => [
                'text' => "IP: {$this->submission->ip_address}",
            ],
            'timestamp' => $this->submission->created_at->toIso8601String(),
        ];

        try {
            $response = Http::timeout(30)->post($this->form->discord_webhook_url, [
                'embeds' => [$embed],
            ]);

            if (!$response->successful()) {
                Log::warning('Discord notification failed', [
                    'form_id' => $this->form->id,
                    'status' => $response->status(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Discord notification error', [
                'form_id' => $this->form->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
