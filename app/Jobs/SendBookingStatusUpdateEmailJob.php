<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Mail\BookingStatusUpdated as BookingStatusUpdatedMail;
use App\Mail\BookingCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingStatusUpdateEmailJob implements ShouldQueue
{
    use Queueable;

    public $booking;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking, string $oldStatus, string $newStatus)
    {
        $this->booking = $booking;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->newStatus === 'completed') {
                // Calculate loyalty points
                $user = $this->booking->user;
                $loyaltyPoints = $user->loyalty_points;
                $loyaltyRemaining = max(0, 10 - $loyaltyPoints);

                // Send completion email with loyalty info
                Mail::to($user->email)
                    ->send(new BookingCompleted($this->booking, $loyaltyPoints, $loyaltyRemaining));
            } else {
                // Send status update email for other status changes
                Mail::to($this->booking->user->email)
                    ->send(new BookingStatusUpdatedMail($this->booking, $this->oldStatus, $this->newStatus));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send booking status update email', [
                'booking_id' => $this->booking->id,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
                'error' => $e->getMessage()
            ]);
        }
    }
}
