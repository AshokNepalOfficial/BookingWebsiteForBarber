<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Mail\BookingConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingConfirmationEmailJob implements ShouldQueue
{
    use Queueable;

    public $booking;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->booking->user->email)
                ->send(new BookingConfirmation($this->booking));
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $this->booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
