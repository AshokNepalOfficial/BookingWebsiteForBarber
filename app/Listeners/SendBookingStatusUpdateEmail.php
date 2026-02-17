<?php

namespace App\Listeners;

use App\Events\BookingStatusUpdated;
use App\Jobs\SendBookingStatusUpdateEmailJob;
use Illuminate\Support\Facades\Log;

class SendBookingStatusUpdateEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingStatusUpdated $event): void
    {
        // Dispatch job to queue (non-blocking, instant response)
        try {
            SendBookingStatusUpdateEmailJob::dispatch($event->booking, $event->oldStatus, $event->newStatus);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch booking status update email job', [
                'booking_id' => $event->booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
