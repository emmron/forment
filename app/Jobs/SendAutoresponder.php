<?php

namespace App\Jobs;

use App\Mail\AutoresponderMail;
use App\Models\Form;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;

class SendAutoresponder implements ShouldQueue
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
        if (!$this->form->autoresponder_enabled) {
            return;
        }

        $email = $this->submission->getEmailField();
        if (!$email) {
            Log::info('Autoresponder skipped - no email field', [
                'form_id' => $this->form->id,
                'submission_id' => $this->submission->id,
            ]);
            return;
        }

        // Validate email address before sending
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Autoresponder skipped - invalid email address', [
                'form_id' => $this->form->id,
                'submission_id' => $this->submission->id,
                'email' => $email,
            ]);
            return;
        }

        $mailer = $this->getMailer();

        try {
            $mailer->to($email)->send(new AutoresponderMail($this->form, $this->submission));

            Log::info('Autoresponder sent', [
                'form_id' => $this->form->id,
                'submission_id' => $this->submission->id,
                'to' => $email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send autoresponder', [
                'form_id' => $this->form->id,
                'submission_id' => $this->submission->id,
                'to' => $email,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function getMailer()
    {
        $smtpConfig = $this->form->getSmtpConfig();

        if (!$smtpConfig) {
            return Mail::mailer();
        }

        $transport = (new EsmtpTransportFactory())->create(new Dsn(
            $smtpConfig['encryption'] === 'ssl' ? 'smtps' : 'smtp',
            $smtpConfig['host'],
            $smtpConfig['username'],
            $smtpConfig['password'],
            $smtpConfig['port']
        ));

        return Mail::mailer()->setSymfonyTransport($transport);
    }
}
