<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\CmsContent;
use App\Models\Setting;
use App\Models\Testimonial;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share CMS content and settings with all views
        View::composer('*', function ($view) {
            // Share CMS content helper
            $view->with('cms', function($section, $key, $default = '') {
                return CmsContent::get($section, $key, $default);
            });
            
            // Share settings helper
            $view->with('setting', function($key, $default = null) {
                return Setting::get($key, $default);
            });
            
            // Share booking settings for modal
            $view->with('bookingSettings', function() {
                static $settings = null;
                if ($settings === null) {
                    $settings = [
                        'enable_barber_selection' => Setting::get('enable_barber_selection', '0'),
                        'max_days_advance' => Setting::get('booking_max_days_advance', '15'),
                        'time_slots' => json_decode(Setting::get('booking_time_slots', '[]'), true) ?: []
                    ];
                }
                return $settings;
            });
            
            // Share testimonials for frontend
            $view->with('testimonials', Testimonial::active()->ordered()->get());
            
            // Share currency formatter
            $view->with('formatPrice', function($amount) {
                $symbol = Setting::get('currency_symbol', '$');
                $position = Setting::get('currency_position', 'before');
                $decimal = Setting::get('decimal_separator', '.');
                $thousand = Setting::get('thousand_separator', ',');
                
                $formatted = number_format($amount, 2, $decimal, $thousand);
                return $position === 'before' ? $symbol . $formatted : $formatted . $symbol;
            });
            
            // Share role permission checker
            $view->with('canAccess', function($permission) {
                if (!Auth::check()) {
                    return false;
                }
                
                $user = Auth::user();
                
                // Define role permissions
                $permissions = [
                    'admin' => ['all'],
                    'manager' => ['bookings', 'customers', 'memberships', 'services', 'transactions', 'reports'],
                    'staff' => ['bookings'],
                    'receptionist' => ['bookings'],
                    'barber' => ['own_bookings'],
                ];
                
                $userPermissions = $permissions[$user->role] ?? [];
                
                return in_array('all', $userPermissions) || in_array($permission, $userPermissions);
            });
        });
    }
}
