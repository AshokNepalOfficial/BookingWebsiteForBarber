<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $loyaltyPoints;
    public $loyaltyRemaining;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, int $loyaltyPoints, int $loyaltyRemaining)
    {
        $this->booking = $booking->load(['user', 'services']);
        $this->loyaltyPoints = $loyaltyPoints;
        $this->loyaltyRemaining = $loyaltyRemaining;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank You for Visiting - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking-completed',
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
