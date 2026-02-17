# Barbershop Booking System - Project Documentation

## Overview
Complete Laravel-based barbershop management system with booking, payment verification, membership management, and loyalty programs.

## System Architecture

### Technology Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Breeze/built-in
- **File Storage**: Laravel Storage (payment proofs)

### Database Schema

#### Core Tables
1. **users** - Customer, staff, and admin accounts
2. **services** - Available barbershop services
3. **bookings** - Appointment bookings
4. **memberships** - Subscription plans
5. **user_memberships** - Active user subscriptions
6. **transactions** - Payment records with audit trail

## Features Implemented

### 1. User Management
- **Roles**: Customer, Member, Staff, Receptionist, Admin
- **Guest Booking**: Auto-creates account with temp password
- **Loyalty Points**: Tracks completed services (10 = 1 free)

### 2. Booking System
- Public booking form (guest or authenticated)
- Multi-step process (service → date/time → details)
- **20-minute edit window** for customers
- Receptionist can edit anytime
- Status workflow: Pending → Confirmed → Completed/Cancelled

### 3. Payment Verification
- **Offline payments only** (no payment gateway)
- Customer uploads payment proof image
- Receptionist records in-person payments
- Phone verification for online transfers
- **Duplicate detection** via transaction reference
- Complete audit trail with verifier tracking

### 4. Membership System
- Multiple subscription plans (Basic, Premium, VIP, Annual)
- Automatic discount application
- Free services tracking
- Priority booking access
- Expiration monitoring with renewal workflow

### 5. Admin Dashboard
- Today's bookings and statistics
- Revenue tracking (daily/monthly)
- Pending confirmations queue
- Payment verification queue
- Popular services analytics
- Weekly booking trends

### 6. Management Interfaces

#### Booking Management
- List with filters (status, date, payment)
- Calendar view
- Full CRUD operations
- Status updates with email triggers

#### Transaction Management
- Payment verification workflow
- Offline payment recording
- Duplicate prevention
- Audit trail viewer
- Fraud detection alerts

#### Membership Management
- Plan configuration
- Member activation/renewal
- Benefits tracking
- Expiration alerts

#### Customer Management
- Profile view with history
- Loyalty points adjustment
- Booking/transaction history
- Membership status

#### Service Management
- Service CRUD
- Pricing updates
- Active/inactive toggle
- Performance metrics

## Key Workflows

### Guest Booking Flow
1. Customer selects service(s)
2. Chooses date and time
3. Enters personal details
4. System checks email → creates account if new
5. Booking created with status "Pending"
6. Emails sent (customer + receptionist)
7. Customer can edit within 20 minutes

### Payment Verification Flow
1. **Option A**: Customer uploads payment proof
   - System stores image securely
   - Notifies receptionist
   - Receptionist verifies via phone/bank
   - Approves or rejects
   
2. **Option B**: In-person payment
   - Customer pays at shop
   - Receptionist records immediately
   - Marked as verified instantly

### Loyalty Program
- Each completed service = +1 point
- At 10 points → 1 free service awarded
- Points reset to 0
- Email shows progress ("7 more to go!")

### Membership Benefits
- Automatic discount on bookings
- Free services deducted from balance
- Priority time slots (if enabled)
- Renewal notifications before expiration

## Security Features

### Authentication
- Role-based access control (RBAC)
- Session management
- Password hashing (bcrypt)

### Authorization
- Time-based permissions (20-min window)
- Booking ownership verification
- Staff override for receptionists

### Data Protection
- Payment proof images secured
- Transaction audit immutable
- Email/phone not exposed publicly
- Encrypted sensitive data

### Fraud Prevention
- Duplicate transaction reference check
- Booking-payment linkage (1:1)
- Temporal analysis for multiple attempts
- Amount matching validation

## API Endpoints

### Public Routes
- `GET /` - Homepage
- `GET /booking/create` - Booking form
- `POST /booking/store` - Submit booking

### Authenticated Routes
- `GET /booking/{id}/edit` - Edit booking (customer)
- `PUT /booking/{id}` - Update booking

### Admin Routes (Role-Protected)
- `GET /admin/dashboard` - Main dashboard
- `GET /admin/bookings` - Booking list
- `POST /admin/transactions/record-offline` - Record payment
- `POST /admin/transactions/{id}/verify` - Verify payment
- `GET /admin/customers` - Customer list
- `GET /admin/memberships` - Membership plans
- `GET /admin/services` - Service management

## Email Notifications

### Triggers
1. **Booking Submitted** → Customer & Receptionist
2. **Booking Confirmed** → Customer (appointment details)
3. **Payment Verified** → Customer (receipt)
4. **Payment Rejected** → Customer (resubmit instructions)
5. **Booking Completed** → Customer (loyalty progress)
6. **Membership Activated** → Member (welcome)
7. **Membership Expiring** → Member (renewal reminder)

## Business Rules

### Booking Rules
- Appointments must be future dates
- Within business hours (Mon-Fri: 9AM-8PM, Sat: 9AM-6PM, Sun: 10AM-4PM)
- No double-booking same time slot
- Customer edit: 20 minutes after creation
- Receptionist edit: anytime

### Payment Rules
- One booking = one payment
- Transaction reference must be unique
- Amount must match booking total
- Verification required before completion

### Loyalty Rules
- 1 completed service = 1 point
- 10 points = 1 free service
- Points reset after redemption
- Works alongside memberships

### Membership Rules
- One active membership per user
- Benefits apply automatically
- Free services tracked per subscription
- Expiration date-based

## Sample Data

### Users (all password: "password")
- admin@barbershop.com - Admin
- receptionist@barbershop.com - Receptionist  
- staff@barbershop.com - Staff
- customer@example.com - Customer (3 loyalty points)

### Services
1. Classic Haircut - $25 (30 min)
2. Beard Trim - $15 (20 min)
3. Hot Towel Shave - $35 (45 min)
4. Hair Coloring - $50 (60 min)
5. Kids Haircut - $18 (25 min)

### Memberships
1. Basic Monthly - $19.99 (10% discount)
2. Premium Monthly - $39.99 (15% + 1 free service)
3. VIP Quarterly - $99.99 (20% + 3 free services)
4. Annual Elite - $299.99 (25% + 12 free services)

## Running the Application

```bash
# Navigate to project
cd barbershop-backend

# Install dependencies (already done)
composer install

# Database already migrated and seeded
# php artisan migrate:fresh --seed

# Start server
php artisan serve

# Access at http://localhost:8000
```

## Project Status

### ✅ Completed
- Database migrations and models
- All controllers with business logic
- Role-based authentication
- Booking workflow (guest + registered)
- Payment verification system
- Transaction audit trail
- Membership management
- Loyalty points automation
- Customer management
- Service management
- Admin dashboard with statistics
- Duplicate payment detection
- 20-minute edit window
- Sample data seeding
- Complete routing structure

### 📋 Ready for Integration
- Frontend HTML templates (provided: frontend.html, admin_panel.html)
- Email notification templates
- Blade views for all controllers
- File upload UI for payment proofs
- Calendar visualization
- Statistical charts

## Notes

- All email sending code is commented (ready to uncomment when SMTP configured)
- Payment proofs stored in `storage/app/public/payment_proofs`
- Logo available at `whitelogo.png` (310x119)
- Frontend and admin templates ready for Blade conversion
- Auth routes expect `routes/auth.php` (Laravel Breeze or custom)

## Development Guidelines

### Adding New Features
1. Create migration: `php artisan make:migration`
2. Update model with relationships
3. Create/update controller
4. Add routes in `web.php`
5. Create Blade views
6. Test workflow

### Testing
- Manual testing via admin panel
- Sample users available for each role
- Database can be reset with `migrate:fresh --seed`

## Support

For implementation questions or customization:
- Check IMPLEMENTATION_SUMMARY.md
- Review controller comments
- Examine model relationships
- Test with seeded sample data