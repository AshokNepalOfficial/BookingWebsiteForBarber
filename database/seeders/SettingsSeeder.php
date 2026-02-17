<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Currency Settings
            ['key' => 'currency_symbol', 'value' => 'Rs ', 'type' => 'text', 'group' => 'currency'],
            ['key' => 'currency_code', 'value' => 'NPR', 'type' => 'text', 'group' => 'currency'],
            ['key' => 'currency_position', 'value' => 'before', 'type' => 'text', 'group' => 'currency'],
            ['key' => 'decimal_separator', 'value' => '.', 'type' => 'text', 'group' => 'currency'],
            ['key' => 'thousand_separator', 'value' => ',', 'type' => 'text', 'group' => 'currency'],
            
            // General Settings
            ['key' => 'site_name', 'value' => 'JB Unisex Salon', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Premium Unisex Salon Experience', 'type' => 'text', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'Asia/Kathmandu', 'type' => 'text', 'group' => 'general'],
            
            // Booking Settings
            ['key' => 'booking_advance_days', 'value' => '30', 'type' => 'number', 'group' => 'booking'],
            ['key' => 'booking_max_days_advance', 'value' => '15', 'type' => 'number', 'group' => 'booking'],
            ['key' => 'booking_cancellation_hours', 'value' => '24', 'type' => 'number', 'group' => 'booking'],
            ['key' => 'slot_duration_minutes', 'value' => '30', 'type' => 'number', 'group' => 'booking'],
            ['key' => 'enable_barber_selection', 'value' => '0', 'type' => 'boolean', 'group' => 'booking'],
            ['key' => 'booking_time_slots', 'value' => json_encode([
                '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
                '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
                '18:00', '18:30', '19:00', '19:30', '20:00'
            ]), 'type' => 'json', 'group' => 'booking'],
            
            // Contact Settings
            ['key' => 'contact_email', 'value' => 'hello@jbunisexsalon.com', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+977-555-123-4567', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => '123 Main Street, Kathmandu, Nepal', 'type' => 'text', 'group' => 'contact'],
            
            // Branding Settings - Logos (Frontend)
            ['key' => 'logo_frontend_light', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            ['key' => 'logo_frontend_dark', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            
            // Branding Settings - Logos (Admin Panel)
            ['key' => 'logo_admin_light', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            ['key' => 'logo_admin_dark', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            
            // Branding Settings - Logos (Auth Pages)
            ['key' => 'logo_auth_light', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            ['key' => 'logo_auth_dark', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            
            // Branding Settings - Favicon
            ['key' => 'favicon_light', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            ['key' => 'favicon_dark', 'value' => '', 'type' => 'image', 'group' => 'branding'],
            
            // Branding Settings - Typography
            ['key' => 'font_primary', 'value' => 'Inter', 'type' => 'text', 'group' => 'branding'],
            ['key' => 'font_secondary', 'value' => 'Playfair Display', 'type' => 'text', 'group' => 'branding'],
            ['key' => 'font_size_base', 'value' => '16', 'type' => 'number', 'group' => 'branding'],
            
            // Branding Settings - Colors
            ['key' => 'color_primary', 'value' => '#D4AF37', 'type' => 'color', 'group' => 'branding'],
            ['key' => 'color_secondary', 'value' => '#1A1A1A', 'type' => 'color', 'group' => 'branding'],
            ['key' => 'color_accent', 'value' => '#FFFFFF', 'type' => 'color', 'group' => 'branding'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
