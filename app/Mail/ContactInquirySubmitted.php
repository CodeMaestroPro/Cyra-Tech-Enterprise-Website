<?php

namespace App\Mail;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquirySubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactInquiry $inquiry,
        public string $inquiryTypeLabel,
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
                new Address($this->inquiry->email, $this->inquiry->name),
            ],
            subject: 'New contact message: '.$this->inquiryTypeLabel.' ('.$this->inquiry->reference.')',
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.contact-inquiry-submitted',
        );
    }
}
