<?php

namespace App\Listeners;

use App\Events\GuestUserCreated;
use App\Jobs\SendGuestAccountCredentialsJob;
use Illuminate\Support\Facades\Log;

class SendGuestAccountCredentials
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
    public function handle(GuestUserCreated $event): void
    {
        // Dispatch job to queue (non-blocking, instant response)
        try {
            SendGuestAccountCredentialsJob::dispatch($event->user, $event->temporaryPassword);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch guest account credentials email job', [
                'user_id' => $event->user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
