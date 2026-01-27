<?php

namespace App\Mail;

use App\Models\Form;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AutoresponderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Form $form,
        public Submission $submission
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->form->autoresponder_subject ?: "Thank you for your submission";

        // Replace placeholders
        $subject = $this->replacePlaceholders($subject);

        return new Envelope(
            subject: $subject,
            replyTo: $this->form->autoresponder_reply_to ? [$this->form->autoresponder_reply_to] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.autoresponder',
            with: [
                'message_content' => $this->replacePlaceholders($this->form->autoresponder_message ?? ''),
            ],
        );
    }

    protected function replacePlaceholders(string $text): string
    {
        $replacements = [
            '{{name}}' => $this->submission->getNameField() ?? 'there',
            '{{email}}' => $this->submission->getEmailField() ?? '',
            '{{form_name}}' => $this->form->name,
        ];

        // Also replace data fields
        foreach ($this->submission->data as $key => $value) {
            if (is_string($value)) {
                $replacements["{{{$key}}}"] = $value;
            }
        }

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }
}
