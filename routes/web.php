<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\QueueProcessorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\GalleryController;
use Illuminate\Support\Facades\Route;



use Illuminate\Support\Facades\Artisan;

// Queue processor route (for shared hosting without cron)
Route::get('/run-queue/{secretKey}', [QueueProcessorController::class, 'process'])
    ->name('queue.process');


Route::get('/create-storage-link', function () {
    // Run the Artisan command
    Artisan::call('storage:link');

    // Optional: get the output
    $output = Artisan::output();

    return "Command executed! Output: <pre>$output</pre>";
});



Route::get('/', function () {
    $services = \App\Models\Service::where('is_active', true)->get();
    $barbers = \App\Models\User::whereIn('role', ['staff', 'receptionist'])->get();
    $galleryItems = \App\Models\GalleryItem::where('is_active', true)
        ->orderBy('display_order')
        ->orderBy('created_at', 'desc')
        ->get();
    return view('frontend.home', compact('services', 'barbers', 'galleryItems'));
})->name('home');

// Gallery page
Route::get('/gallery', function () {
    $categories = \App\Models\GalleryItem::where('is_active', true)
        ->select('category')
        ->distinct()
        ->pluck('category');
    
    $category = request('category');
    
    $galleryItems = \App\Models\GalleryItem::where('is_active', true)
        ->when($category, function ($query, $category) {
            return $query->where('category', $category);
        })
        ->orderBy('display_order')
        ->orderBy('created_at', 'desc')
        ->paginate(16);
    
    return view('frontend.gallery', compact('galleryItems', 'categories', 'category'));
})->name('gallery');

// Booking routes (public and authenticated)
Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
Route::get('/booking/{id}/success', function($id) {
    $booking = \App\Models\Booking::findOrFail($id);
    return view('booking.success', compact('booking'));
})->name('booking.success');

// Customer routes (authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/booking/{id}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');
    
    // Profile & Dashboard
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\ProfileController::class, 'dashboard'])->name('dashboard');
        Route::get('/bookings', [App\Http\Controllers\ProfileController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/{id}', [App\Http\Controllers\ProfileController::class, 'showBooking'])->name('bookings.show');
        Route::get('/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::get('/membership', [App\Http\Controllers\ProfileController::class, 'membership'])->name('membership');
        Route::get('/transactions', [App\Http\Controllers\ProfileController::class, 'transactions'])->name('transactions');
    });
});

// Receptionist routes
Route::middleware(['auth', 'role:receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Receptionist\DashboardController::class, 'index'])->name('dashboard');
    
    // Receptionist can access bookings (use same admin controllers)
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingManagementController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingManagementController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/edit', [BookingManagementController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{id}', [BookingManagementController::class, 'update'])->name('bookings.update');
    Route::put('/bookings/{id}/status', [BookingManagementController::class, 'updateStatus'])->name('bookings.updateStatus');
});

// Barber routes
Route::middleware(['auth', 'role:barber'])->prefix('barber')->name('barber.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Barber\DashboardController::class, 'index'])->name('dashboard');
    
    // Barber can view and update their own bookings
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{id}/status', [BookingManagementController::class, 'updateStatus'])->name('bookings.updateStatus');
});

// Admin routes (role-based)
Route::middleware(['auth', 'role:admin,manager,staff'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
    
    // Booking Management
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingManagementController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingManagementController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/edit', [BookingManagementController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{id}', [BookingManagementController::class, 'update'])->name('bookings.update');
    Route::put('/bookings/{id}/status', [BookingManagementController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('/bookings/{id}', [BookingManagementController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/calendar/view', [BookingManagementController::class, 'calendar'])->name('bookings.calendar');
    
    // Transaction Management
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/pending/list', [TransactionController::class, 'pendingPayments'])->name('transactions.pending');
    Route::post('/transactions/record-offline', [TransactionController::class, 'recordOfflinePayment'])->name('transactions.recordOffline');
    Route::post('/transactions/{id}/upload-proof', [TransactionController::class, 'uploadProof'])->name('transactions.uploadProof');
    Route::post('/transactions/{id}/verify', [TransactionController::class, 'verify'])->name('transactions.verify');
    Route::get('/transactions/audit/view', [TransactionController::class, 'audit'])->name('transactions.audit');
    
    // Membership Management
    Route::get('/memberships', [MembershipController::class, 'index'])->name('memberships.index');
    Route::get('/memberships/create', [MembershipController::class, 'create'])->name('memberships.create');
    Route::post('/memberships', [MembershipController::class, 'store'])->name('memberships.store');
    Route::get('/memberships/{id}/edit', [MembershipController::class, 'edit'])->name('memberships.edit');
    Route::put('/memberships/{id}', [MembershipController::class, 'update'])->name('memberships.update');
    Route::get('/memberships/members/list', [MembershipController::class, 'members'])->name('memberships.members');
    Route::post('/memberships/activate', [MembershipController::class, 'activate'])->name('memberships.activate');
    Route::post('/memberships/{id}/renew', [MembershipController::class, 'renew'])->name('memberships.renew');
    Route::post('/memberships/{id}/cancel', [MembershipController::class, 'cancel'])->name('memberships.cancel');
    Route::get('/memberships/expiring/list', [MembershipController::class, 'expiring'])->name('memberships.expiring');
    Route::post('/memberships/check-expired', [MembershipController::class, 'checkExpired'])->name('memberships.checkExpired');
    
    // Service Management
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update');
    Route::post('/services/{id}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggleStatus');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::get('/services/performance/view', [ServiceController::class, 'performance'])->name('services.performance');
    
    // Customer Management
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::get('/customers/{id}/loyalty', [CustomerController::class, 'loyaltyPoints'])->name('customers.loyalty');
    Route::post('/customers/{id}/adjust-loyalty', [CustomerController::class, 'adjustLoyaltyPoints'])->name('customers.adjustLoyalty');
    Route::get('/customers/{id}/bookings', [CustomerController::class, 'bookingHistory'])->name('customers.bookings');
    Route::get('/customers/{id}/memberships', [CustomerController::class, 'membershipHistory'])->name('customers.memberships');
    Route::get('/customers/{id}/transactions', [CustomerController::class, 'transactionHistory'])->name('customers.transactions');
    
    // CMS - Frontend Content Management
    Route::get('/cms', [App\Http\Controllers\Admin\CmsController::class, 'index'])->name('cms.index');
    Route::get('/cms/{section}', [App\Http\Controllers\Admin\CmsController::class, 'edit'])->name('cms.edit');
    Route::put('/cms/{section}', [App\Http\Controllers\Admin\CmsController::class, 'update'])->name('cms.update');
    
    // Testimonials Management
    Route::resource('testimonials', App\Http\Controllers\Admin\TestimonialController::class)->except(['show']);
    
    // Settings Management
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    // Staff Management
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}', [StaffController::class, 'show'])->name('staff.show');
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::post('/staff/{id}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggleStatus');
    
    // Roles & Permissions Management
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('/roles/{id}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggleStatus');
    
    // Reports & Analytics
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/visitors', [ReportController::class, 'visitors'])->name('reports.visitors');
    Route::get('/reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
    
    // Pages Management
    Route::resource('pages', PageController::class)->except(['show']);
    
    // Blog Management
    Route::resource('blogs', BlogController::class)->except(['show']);
    
    // Gallery Management
    Route::resource('gallery', GalleryController::class)->except(['show']);
    Route::post('/gallery/reorder', [GalleryController::class, 'reorder'])->name('gallery.reorder');
});

require __DIR__.'/auth.php';


use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

Route::get('/test-mail', function () {
    Mail::to('ashoknepal008@gmail.com')->send(new TestMail());

    return 'Test mail sent (check inbox or spam)';
});



