# Real-Time Booking Notification System

## Overview
The system is configured to send real-time notifications and emails to staff members when a new booking is created.

## Components Implemented

### 1. Event Broadcasting
**File:** `app/Events/BookingCreated.php`
- Broadcasts to `staff-notifications` private channel
- Sends booking data including customer, services, date, and time
- Event name: `booking.created`

### 2. Database Notifications
**File:** `app/Notifications/NewBookingNotification.php`
- Sends email notifications to staff members
- Stores notifications in database for in-app display
- Queued for performance (implements `ShouldQueue`)

### 3. Controller Integration
**Files:**
- `app/Http/Controllers/BookingController.php` (frontend bookings)
- `app/Http/Controllers/Admin/BookingManagementController.php` (admin created bookings)

Both controllers now:
- Dispatch `BookingCreated` event for real-time broadcasts
- Send notifications to staff with booking permissions

### 4. Staff Notification Logic
Notifications are sent to staff members who:
- Have `is_active = true`
- Have a role with `is_active = true`
- Have role permissions for: `view_bookings`, `edit_bookings`, or `confirm_bookings`

### 5. Database Migration
**File:** `database/migrations/2026_01_20_211558_create_notifications_table.php`
- Creates `notifications` table for storing notifications
- Stores notification data in JSON format

## Setup for Real-Time Broadcasting (Future)

### Option 1: Laravel Reverb (Recommended)
```bash
composer require laravel/reverb
php artisan reverb:install
php artisan reverb:start
```

Update `.env`:
```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http
```

### Option 2: Pusher
```bash
composer require pusher/pusher-php-server
```

Update `.env`:
```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
```

## Frontend Integration

### 1. Subscribe to Private Channel
```javascript
Echo.private('staff-notifications')
    .listen('.booking.created', (e) => {
        console.log('New booking:', e);
        // Show toast notification
        // Update booking list
        // Play sound alert
    });
```

### 2. Add to Admin Layout
In `resources/views/layouts/admin.blade.php`:

```html
@vite(['resources/css/app.css', 'resources/js/app.js'])

<script>
// Real-time notifications
@if(auth()->guard('staff')->check())
    Echo.private('staff-notifications')
        .listen('.booking.created', (data) => {
            // Show notification toast
            showNotification({
                title: 'New Booking',
                message: `${data.customer} - ${data.services.join(', ')}`,
                date: data.date,
                time: data.time,
                link: `/admin/bookings/${data.id}`
            });
            
            // Play notification sound
            playNotificationSound();
            
            // Update notification count badge
            updateNotificationBadge();
        });
@endif
</script>
```

## Email Configuration

### Current Setup
**SMTP Configuration in `.env`:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=465
MAIL_USERNAME=info@example.com
MAIL_PASSWORD=your-app-secret
MAIL_FROM_ADDRESS="info@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Email Template
**File:** `app/Notifications/NewBookingNotification.php`
- Subject: "New Booking Alert - [App Name]"
- Includes customer details, services, date, and time
- Has action button linking to booking detail page

## Queue Workers

Start queue worker to process notifications:
```bash
php artisan queue:work
```

For production, use supervisor or systemd to keep queue workers running.

## Testing

### 1. Create a Test Booking
```bash
# Via frontend or admin panel
# OR via Tinker:
php artisan tinker

$booking = App\Models\Booking::create([
    'user_id' => 1,
    'appointment_date' => now()->addDays(1),
    'appointment_time' => '10:00:00',
    'status' => 'pending',
    'payment_status' => 'pending',
]);

$booking->services()->attach([1, 2]);

event(new App\Events\BookingCreated($booking));
```

### 2. Check Notifications
```bash
# Check database notifications
php artisan tinker
App\Models\Staff::first()->notifications;

# Check mail queue
SELECT * FROM jobs;
```

## Notification Channels

### Database Notifications
Stored in `notifications` table:
- `id`: UUID
- `type`: Notification class name
- `notifiable_type`: Staff model
- `notifiable_id`: Staff member ID
- `data`: JSON with booking details
- `read_at`: Timestamp when marked as read

### Email Notifications
Sent via configured MAIL settings in `.env`

### Broadcast Notifications (Future)
When WebSocket server is configured, real-time updates will be pushed to connected staff members.

## Security

### Private Channel Authorization
Define in `routes/channels.php`:
```php
Broadcast::channel('staff-notifications', function ($staff) {
    return $staff instanceof \App\Models\Staff && $staff->is_active;
});
```

## API Endpoints (Future)

### Get Unread Notifications
```
GET /api/staff/notifications
```

### Mark Notification as Read
```
POST /api/staff/notifications/{id}/read
```

### Mark All as Read
```
POST /api/staff/notifications/read-all
```
