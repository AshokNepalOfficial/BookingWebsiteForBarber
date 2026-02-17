<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Jobs\NotifyStaffAboutNewBookingJob;
use Illuminate\Support\Facades\Log;

class NotifyStaffAboutNewBooking
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
    public function handle(BookingCreated $event): void
    {
        // Dispatch job to queue (non-blocking, instant response)
        try {
            NotifyStaffAboutNewBookingJob::dispatch($event->booking);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch staff notification job', [
                'booking_id' => $event->booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
