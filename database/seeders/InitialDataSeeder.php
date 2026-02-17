<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@barbershop.com',
            'phone_no' => '555-0100',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_guest' => false,
        ]);

        // Create receptionist
        User::create([
            'first_name' => 'John',
            'last_name' => 'Receptionist',
            'email' => 'receptionist@barbershop.com',
            'phone_no' => '555-0101',
            'password' => Hash::make('password'),
            'role' => 'receptionist',
            'is_guest' => false,
        ]);

        // Create staff/barbers
        User::create([
            'first_name' => 'James',
            'last_name' => 'Sterling',
            'email' => 'james@barbershop.com',
            'phone_no' => '555-0102',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_guest' => false,
        ]);

        User::create([
            'first_name' => 'Marcus',
            'last_name' => 'Thorne',
            'email' => 'marcus@barbershop.com',
            'phone_no' => '555-0103',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_guest' => false,
        ]);

        // Create test customer
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'customer@example.com',
            'phone_no' => '555-0200',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_guest' => false,
        ]);

        // Create services
        $services = [
            [
                'title' => 'Classic Haircut',
                'sub_title' => 'Scissor cut & styling',
                'price' => 45.00,
                'duration_minutes' => 45,
                'icon' => 'fa-cut',
                'is_active' => true,
            ],
            [
                'title' => 'Beard Sculpting',
                'sub_title' => 'Shape, trim & oil',
                'price' => 40.00,
                'duration_minutes' => 30,
                'icon' => 'fa-scissors',
                'is_active' => true,
            ],
            [
                'title' => 'Hot Towel Shave',
                'sub_title' => 'Straight razor finish',
                'price' => 35.00,
                'duration_minutes' => 30,
                'icon' => 'fa-pump-soap',
                'is_active' => true,
            ],
            [
                'title' => 'Fade & Taper',
                'sub_title' => 'Skin fade precision',
                'price' => 50.00,
                'duration_minutes' => 45,
                'icon' => 'fa-cut',
                'is_active' => true,
            ],
            [
                'title' => 'The Gentleman\'s Full Experience',
                'sub_title' => 'Haircut + Shave + Facial + Drink',
                'price' => 120.00,
                'duration_minutes' => 90,
                'icon' => 'fa-crown',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('✅ Initial data seeded successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('Admin: admin@barbershop.com / password');
        $this->command->info('Receptionist: receptionist@barbershop.com / password');
        $this->command->info('Customer: customer@example.com / password');
    }
}
