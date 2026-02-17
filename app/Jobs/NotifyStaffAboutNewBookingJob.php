<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class NotifyStaffAboutNewBookingJob implements ShouldQueue
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
            // Get all staff/admin users with roles that have booking permissions
            $staff = User::where('is_active', true)
                ->whereIn('role', ['admin', 'receptionist', 'staff', 'manager', 'barber'])
                ->whereHas('roleDetails', function($query) {
                    $query->where('is_active', true)
                        ->whereHas('permissions', function($q) {
                            $q->whereIn('name', ['view_bookings', 'edit_bookings', 'confirm_bookings']);
                        });
                })
                ->get();

            // Send notification to each staff member
            foreach ($staff as $staffMember) {
                $staffMember->notify(new NewBookingNotification($this->booking));
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify staff about new booking', [
                'booking_id' => $this->booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
