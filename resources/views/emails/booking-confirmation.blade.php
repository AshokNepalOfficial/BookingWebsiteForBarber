<x-mail::message>
# Booking Request Received

Dear {{ $booking->user->first_name }} {{ $booking->user->last_name }},

Thank you for choosing **JB Barber Unisex Salon**! We have received your booking request.

## Booking Details

**Services:**
@foreach($booking->services as $service)
- {{ $service->title }} ({!! $formatPrice($service->price) !!})
@endforeach

**Date:** {{ $booking->appointment_date->format('l, F j, Y') }}
**Time:** {{ $booking->appointment_time }}

@if($booking->barber)
**Barber:** {{ $booking->barber->first_name }} {{ $booking->barber->last_name }}
@else
**Barber:** Any available
@endif

@if($booking->special_request)
**Special Request:** {{ $booking->special_request }}
@endif

## What's Next?

Our team will review your booking and contact you soon via email or phone to confirm your appointment.

**Note:** If you need to make changes to your booking, you can edit it within 20 minutes of creation from your account.

<x-mail::button :url="url('/')">
View Booking
</x-mail::button>

Thank you,<br>
{{ config('app.name') }}
</x-mail::message>
