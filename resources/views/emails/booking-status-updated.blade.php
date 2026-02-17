<x-mail::message>
# Booking Status Updated

Dear {{ $booking->user->first_name }} {{ $booking->user->last_name }},

Your booking status has been updated.

## Booking Details

**Booking ID:** #{{ $booking->id }}
**Services:**
@foreach($booking->services as $service)
- {{ $service->title }}
@endforeach

**Date:** {{ $booking->appointment_date->format('l, F j, Y') }}
**Time:** {{ $booking->appointment_time }}

@if($booking->staff)
**Assigned Staff:** {{ $booking->staff->first_name }} {{ $booking->staff->last_name }}
@endif

## Status Change

**Previous Status:** {{ ucfirst($oldStatus) }}
**Current Status:** {{ ucfirst($newStatus) }}

@if($newStatus === 'confirmed')
✅ **Your booking has been confirmed!**

We're looking forward to seeing you. Please arrive 5 minutes before your scheduled time.
@elseif($newStatus === 'cancelled')
❌ **Your booking has been cancelled.**

If you have any questions or would like to reschedule, please don't hesitate to contact us.
@elseif($newStatus === 'pending')
⏳ **Your booking is pending confirmation.**

We'll review your request and get back to you soon.
@endif

<x-mail::button :url="url('/')">
View Booking Details
</x-mail::button>

Thank you,<br>
{{ config('app.name') }}
</x-mail::message>
