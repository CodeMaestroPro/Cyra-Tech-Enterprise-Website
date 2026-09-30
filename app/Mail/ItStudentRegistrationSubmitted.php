<?php

namespace App\Mail;

use App\Models\ItStudentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ItStudentRegistrationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ItStudentRegistration $registration,
        public string $interestLabel,
        public string $levelLabel,
        public string $availabilityLabel,
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
            subject: 'New IT student registration ('.$this->registration->reference.')',
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.it-student-registration-submitted',
        );
    }
}
