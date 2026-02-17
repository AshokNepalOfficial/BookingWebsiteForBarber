<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking->load(['user', 'services', 'barber']);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $servicesList = $this->booking->services->pluck('title')->join(', ');
        
        return (new MailMessage)
                    ->subject('New Booking Alert - ' . config('app.name'))
                    ->greeting('Hello ' . $notifiable->first_name . '!')
                    ->line('A new booking has been created and requires your attention.')
                    ->line('**Customer:** ' . $this->booking->user->first_name . ' ' . $this->booking->user->last_name)
                    ->line('**Services:** ' . $servicesList)
                    ->line('**Date:** ' . $this->booking->appointment_date->format('l, F j, Y'))
                    ->line('**Time:** ' . $this->booking->appointment_time)
                    ->action('View Booking', url('/admin/bookings/' . $this->booking->id))
                    ->line('Please confirm or manage this booking as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'customer_name' => $this->booking->user->first_name . ' ' . $this->booking->user->last_name,
            'services' => $this->booking->services->pluck('title')->toArray(),
            'appointment_date' => $this->booking->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->booking->appointment_time,
            'message' => 'New booking created',
        ];
    }
}
