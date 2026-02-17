<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsContent;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            // Hero Section
            [
                'section' => 'hero',
                'key' => 'title',
                'value' => 'Justin Barber Unisex Salon',
                'type' => 'text',
                'order' => 0,
            ],
            [
                'section' => 'hero',
                'key' => 'subtitle',
                'value' => 'Experience timeless grooming excellence. Where tradition meets modern sophistication.',
                'type' => 'textarea',
                'order' => 1,
            ],
            [
                'section' => 'hero',
                'key' => 'cta_text',
                'value' => 'Book Your Appointment',
                'type' => 'text',
                'order' => 2,
            ],

            // About Section
            [
                'section' => 'about',
                'key' => 'title',
                'value' => 'Crafting Distinguished Looks Since 1985',
                'type' => 'text',
                'order' => 0,
            ],
            [
                'section' => 'about',
                'key' => 'description',
                'value' => 'At Justin Barber Unisex Salon, we believe that every man deserves to look and feel his absolute best. Our master barbers combine decades of experience with contemporary techniques to deliver exceptional grooming services in a refined, welcoming atmosphere.',
                'type' => 'textarea',
                'order' => 1,
            ],

            // Services Intro
            [
                'section' => 'services_intro',
                'key' => 'title',
                'value' => 'Our Premium Services',
                'type' => 'text',
                'order' => 0,
            ],
            [
                'section' => 'services_intro',
                'key' => 'subtitle',
                'value' => 'From classic cuts to modern styles, we offer a comprehensive range of grooming services tailored to the modern gentleman.',
                'type' => 'textarea',
                'order' => 1,
            ],

            // Testimonials
            [
                'section' => 'testimonials',
                'key' => 'title',
                'value' => 'What Our Clients Say',
                'type' => 'text',
                'order' => 0,
            ],
            [
                'section' => 'testimonials',
                'key' => 'subtitle',
                'value' => 'Hear from our satisfied gentlemen',
                'type' => 'text',
                'order' => 1,
            ],

            // Contact
            [
                'section' => 'contact',
                'key' => 'phone',
                'value' => '+1 (555) 123-4567',
                'type' => 'text',
                'order' => 0,
            ],
            [
                'section' => 'contact',
                'key' => 'email',
                'value' => 'hello@jbunisexsalon.com',
                'type' => 'text',
                'order' => 1,
            ],
            [
                'section' => 'contact',
                'key' => 'address',
                'value' => '123 Main Street, Downtown, New York, NY 10001',
                'type' => 'textarea',
                'order' => 2,
            ],
            [
                'section' => 'contact',
                'key' => 'hours',
                'value' => "Monday - Friday: 9:00 AM - 8:00 PM\nSaturday: 9:00 AM - 6:00 PM\nSunday: 10:00 AM - 4:00 PM",
                'type' => 'textarea',
                'order' => 3,
            ],

            // Footer
            [
                'section' => 'footer',
                'key' => 'copyright',
                'value' => '© 2026 Justin Barber Unisex Salon. All rights reserved.',
                'type' => 'text',
                'order' => 0,
            ],
            [
                'section' => 'footer',
                'key' => 'facebook',
                'value' => 'https://facebook.com/thegentlemanscut',
                'type' => 'url',
                'order' => 1,
            ],
            [
                'section' => 'footer',
                'key' => 'instagram',
                'value' => 'https://instagram.com/thegentlemanscut',
                'type' => 'url',
                'order' => 2,
            ],
            [
                'section' => 'footer',
                'key' => 'twitter',
                'value' => 'https://twitter.com/thegentlemanscut',
                'type' => 'url',
                'order' => 3,
            ],
        ];

        foreach ($contents as $content) {
            CmsContent::updateOrCreate(
                [
                    'section' => $content['section'],
                    'key' => $content['key'],
                ],
                $content
            );
        }
    }
}
