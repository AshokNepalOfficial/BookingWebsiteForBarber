<?php

/**
 * Test script for guest booking and user creation flow
 * Run with: php artisan test:guest-booking
 */

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Events\GuestUserCreated;
use App\Events\BookingCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestBookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_create_booking_and_receive_credentials()
    {
        // Fake events and queue
        Event::fake([GuestUserCreated::class, BookingCreated::class]);
        Queue::fake();

        // Create a test service
        $service = Service::create([
            'title' => 'Haircut',
            'description' => 'Basic haircut',
            'price' => 25.00,
            'duration' => 30,
            'is_active' => true,
        ]);

        // Guest booking data
        $bookingData = [
            'service_ids' => json_encode([$service->id]),
            'appointment_date' => now()->addDays(1)->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone_no' => '1234567890',
            'special_request' => 'Please use scissors only',
        ];

        // Submit booking
        $response = $this->post(route('bookings.store'), $bookingData);

        // Assert redirect to success page
        $response->assertRedirect();
        
        // Assert user was created
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'role' => 'customer',
            'is_guest' => true,
        ]);

        // Assert booking was created
        $this->assertDatabaseHas('bookings', [
            'appointment_date' => now()->addDays(1)->format('Y-m-d'),
            'appointment_time' => '10:00:00',
        ]);

        // Assert events were dispatched
        Event::assertDispatched(GuestUserCreated::class);
        Event::assertDispatched(BookingCreated::class);
    }

    public function test_existing_user_does_not_create_duplicate_account()
    {
        Event::fake();

        // Create existing user
        $existingUser = User::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone_no' => '9876543210',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'is_guest' => false,
            'loyalty_points' => 5,
        ]);

        $service = Service::create([
            'title' => 'Shave',
            'description' => 'Clean shave',
            'price' => 15.00,
            'duration' => 15,
            'is_active' => true,
        ]);

        // Try to book with existing email
        $bookingData = [
            'service_ids' => json_encode([$service->id]),
            'appointment_date' => now()->addDays(1)->format('Y-m-d'),
            'appointment_time' => '11:00:00',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone_no' => '9876543210',
        ];

        $response = $this->post(route('bookings.store'), $bookingData);

        // Assert no new user created
        $this->assertEquals(1, User::where('email', 'jane.smith@example.com')->count());

        // Assert GuestUserCreated event was NOT dispatched
        Event::assertNotDispatched(GuestUserCreated::class);

        // But BookingCreated should be dispatched
        Event::assertDispatched(BookingCreated::class);
    }
}
