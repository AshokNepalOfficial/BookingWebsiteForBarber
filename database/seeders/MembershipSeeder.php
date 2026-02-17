<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $memberships = [
            [
                'membership_name' => 'Basic Monthly',
                'description' => 'Get 10% discount on all services for one month. Perfect for regular customers.',
                'duration_days' => 30,
                'price' => 19.99,
                'discount_percentage' => 10.00,
                'free_services_count' => 0,
                'priority_booking' => false,
                'is_active' => true,
            ],
            [
                'membership_name' => 'Premium Monthly',
                'description' => 'Get 15% discount plus 1 free haircut per month. Includes priority booking.',
                'duration_days' => 30,
                'price' => 39.99,
                'discount_percentage' => 15.00,
                'free_services_count' => 1,
                'priority_booking' => true,
                'is_active' => true,
            ],
            [
                'membership_name' => 'VIP Quarterly',
                'description' => 'Get 20% discount plus 3 free services for 3 months. Priority booking and exclusive perks.',
                'duration_days' => 90,
                'price' => 99.99,
                'discount_percentage' => 20.00,
                'free_services_count' => 3,
                'priority_booking' => true,
                'is_active' => true,
            ],
            [
                'membership_name' => 'Annual Elite',
                'description' => 'Best value! Get 25% discount plus 12 free services for 1 year. VIP treatment guaranteed.',
                'duration_days' => 365,
                'price' => 299.99,
                'discount_percentage' => 25.00,
                'free_services_count' => 12,
                'priority_booking' => true,
                'is_active' => true,
            ],
            [
                'membership_name' => 'Weekend Warrior',
                'description' => 'Weekend-only membership with 12% discount. Valid for Saturday and Sunday appointments.',
                'duration_days' => 30,
                'price' => 24.99,
                'discount_percentage' => 12.00,
                'free_services_count' => 0,
                'priority_booking' => false,
                'is_active' => true,
            ],
        ];

        foreach ($memberships as $membership) {
            Membership::updateOrCreate(
                ['membership_name' => $membership['membership_name']],
                $membership
            );
        }

        $this->command->info('✅ Membership plans seeded successfully!');
        $this->command->info('Created ' . count($memberships) . ' membership plans.');
    }
}
