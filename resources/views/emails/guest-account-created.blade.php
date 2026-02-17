<x-mail::message>
# Welcome to {{ config('app.name') }}!

Dear {{ $user->first_name }} {{ $user->last_name }},

Thank you for booking with us! We've created an account for you so you can easily manage your bookings and track your loyalty rewards.

## Your Login Credentials

**Email:** {{ $user->email }}  
**Temporary Password:** `{{ $temporaryPassword }}`

<x-mail::button :url="route('login')">
Login to Your Account
</x-mail::button>

## Important Security Notice

🔒 For your security, please change your password after logging in:
1. Log in using the credentials above
2. Go to your Profile Settings
3. Update your password to something memorable

## What You Can Do

With your account, you can:
- ✅ View and manage your bookings
- ✅ Track your loyalty points (earn 1 point per visit, get a free service at 10 points!)
- ✅ Update your profile information
- ✅ View your booking history
- ✅ Book new appointments faster

## Need Help?

If you have any questions or need assistance, feel free to contact us.

Thank you for choosing {{ config('app.name') }}!

Best regards,<br>
{{ config('app.name') }} Team
</x-mail::message>
