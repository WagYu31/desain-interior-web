<?php

namespace App\Mail;

use App\Models\ContactMessage; // Import model ContactMessage
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Instance dari pesan kontak yang akan dikirim.
     *
     * @var \App\Models\ContactMessage
     */
    public $contactMessage; // Buat properti ini menjadi public

    /**
     * Create a new message instance.
     *
     * @param \App\Models\ContactMessage $contactMessage
     * @return void
     */
    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Kustomisasi subjek dan alamat "Reply-To"
        return new Envelope(
            replyTo: $this->contactMessage->email, 
            subject: 'Pesan Kontak Baru: ' . $this->contactMessage->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Tentukan view Blade mana yang akan digunakan untuk email ini
        return new Content(
            markdown: 'emails.contact.new-message',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}