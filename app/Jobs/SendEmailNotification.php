<?php

namespace App\Jobs;

use App\Mail\NewSubmissionNotification;
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

class SendEmailNotification implements ShouldQueue
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
        if (!$this->form->email_notifications) {
            return;
        }

        $to = $this->form->getNotificationEmailAddress();

        if (!$to) {
            Log::warning('No notification email configured', [
                'form_id' => $this->form->id,
                'submission_id' => $this->submission->id,
            ]);
            return;
        }

        $mailer = $this->getMailer();

        try {
            $mailer->to($to)->send(new NewSubmissionNotification($this->form, $this->submission));

            Log::info('Email notification sent', [
                'form_id' => $this->form->id,
                'submission_id' => $this->submission->id,
                'to' => $to,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'form_id' => $this->form->id,
                'submission_id' => $this->submission->id,
                'to' => $to,
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
