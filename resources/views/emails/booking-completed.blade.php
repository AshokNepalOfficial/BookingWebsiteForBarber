<x-mail::message>
# Thank You for Your Visit!

Dear {{ $booking->user->first_name }} {{ $booking->user->last_name }},

Thank you for visiting **JB Barber Unisex Salon**! We hope you enjoyed your experience with us.

## Service Summary

**Services Completed:**
@foreach($booking->services as $service)
- {{ $service->title }}
@endforeach

**Date:** {{ $booking->appointment_date->format('l, F j, Y') }}

## Loyalty Rewards Program

🎉 **Great news!** You've earned loyalty points with this visit.

**Your Loyalty Status:**
- Total Points: {{ $loyaltyPoints }}
- Visits Remaining for Free Service: **{{ $loyaltyRemaining }}**

@if($loyaltyRemaining == 0)
🎊 **Congratulations!** You've earned a FREE haircut! Contact us to redeem your reward on your next visit.
@else
Only {{ $loyaltyRemaining }} more {{ $loyaltyRemaining == 1 ? 'visit' : 'visits' }} until your next FREE service!
@endif

<x-mail::button :url="url('/')">
Book Your Next Appointment
</x-mail::button>

We look forward to seeing you again soon!

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
