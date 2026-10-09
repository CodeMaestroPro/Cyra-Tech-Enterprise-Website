<?php

namespace App\Mail;

use App\Models\StudentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentRegistrationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public StudentRegistration $registration,
        public string $programLabel,
        public string $levelLabel,
        public string $amountFormatted,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.from.address'),
                (string) config('mail.from.name'),
            ),
            replyTo: [
                new Address($this->registration->email, $this->registration->name),
            ],
            subject: 'New paid student registration ('.$this->registration->reference.')',
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.student-registration-submitted',
        );
    }
}
