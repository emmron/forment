<?php

namespace App\Mail;

use App\Models\Form;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewSubmissionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Form $form,
        public Submission $submission
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New submission: {$this->form->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-submission',
        );
    }
}
