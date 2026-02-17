<?php

namespace App\Providers;

use App\Events\BookingCreated;
use App\Events\BookingStatusUpdated;
use App\Events\GuestUserCreated;
use App\Listeners\SendBookingConfirmationEmail;
use App\Listeners\NotifyStaffAboutNewBooking;
use App\Listeners\SendBookingStatusUpdateEmail;
use App\Listeners\SendGuestAccountCredentials;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Default Laravel registration event
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Booking Events
        BookingCreated::class => [
            SendBookingConfirmationEmail::class,
            NotifyStaffAboutNewBooking::class,
        ],
        
        BookingStatusUpdated::class => [
            SendBookingStatusUpdateEmail::class,
        ],
        
        // Guest User Events
        GuestUserCreated::class => [
            SendGuestAccountCredentials::class, // Ensure listener queues the email
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // No additional boot logic needed
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
