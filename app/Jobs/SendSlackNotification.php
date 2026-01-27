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

class SendSlackNotification implements ShouldQueue
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
        if (!$this->form->slack_enabled || !$this->form->slack_webhook_url) {
            return;
        }

        $fields = [];
        foreach ($this->submission->data as $key => $value) {
            $displayValue = is_array($value) ? json_encode($value) : (string)$value;
            $fields[] = [
                'type' => 'mrkdwn',
                'text' => "*{$key}:*\n{$displayValue}",
            ];
        }

        // Slack blocks have a max of 10 fields per section
        $fieldSections = array_chunk($fields, 10);

        $blocks = [
            [
                'type' => 'header',
                'text' => [
                    'type' => 'plain_text',
                    'text' => "New submission: {$this->form->name}",
                ],
            ],
        ];

        foreach ($fieldSections as $sectionFields) {
            $blocks[] = [
                'type' => 'section',
                'fields' => $sectionFields,
            ];
        }

        $blocks[] = [
            'type' => 'context',
            'elements' => [
                [
                    'type' => 'mrkdwn',
                    'text' => "Submitted at {$this->submission->created_at->format('M j, Y g:i A')} | IP: {$this->submission->ip_address}",
                ],
            ],
        ];

        try {
            $response = Http::timeout(30)->post($this->form->slack_webhook_url, [
                'blocks' => $blocks,
            ]);

            if (!$response->successful()) {
                Log::warning('Slack notification failed', [
                    'form_id' => $this->form->id,
                    'status' => $response->status(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Slack notification error', [
                'form_id' => $this->form->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
