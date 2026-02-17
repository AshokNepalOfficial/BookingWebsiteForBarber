@extends('layouts.frontend_custom_2')

@section('title', 'Edit Profile | ' . ($setting('site_name') ?? 'JB Barber Unisex Salon'))

@section('content')
<div class="pt-32 pb-20 bg-dark-800 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-dark-900 rounded-xl border border-white/5 overflow-hidden sticky top-32">
                    <div class="p-6 text-center border-b border-white/5">
                        <div class="w-20 h-20 bg-gold-500 rounded-full mx-auto flex items-center justify-center text-dark-900 text-3xl font-bold mb-4">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                        <h2 class="text-xl font-serif text-white">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    </div>
                    <nav class="p-4 space-y-1">
                        <a href="{{ route('profile.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-th-large w-5"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.bookings') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="far fa-calendar-alt w-5"></i> My Bookings
                        </a>
                        <a href="{{ route('profile.membership') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-crown w-5"></i> Membership
                        </a>
                        <a href="{{ route('profile.transactions') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-history w-5"></i> Transactions
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-gold-500 text-dark-900 font-bold transition-all">
                            <i class="fas fa-user-edit w-5"></i> Edit Profile
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="mb-8">
                    <h1 class="text-3xl font-serif text-white mb-2">Account Settings</h1>
                    <p class="text-gray-400">Update your personal information and security settings.</p>
                </div>

                <div class="space-y-8">
                    <!-- Profile Information -->
                    <div class="bg-dark-900 rounded-xl border border-white/5 overflow-hidden">
                        <div class="p-6 border-b border-white/5">
                            <h3 class="font-serif text-lg text-white">Profile Information</h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm text-gray-400 uppercase tracking-widest font-bold">First Name</label>
                                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full bg-dark-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-gold-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm text-gray-400 uppercase tracking-widest font-bold">Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full bg-dark-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-gold-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm text-gray-400 uppercase tracking-widest font-bold">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-dark-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-gold-500 outline-none transition-all">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm text-gray-400 uppercase tracking-widest font-bold">Phone Number</label>
                                        <input type="text" name="phone_no" value="{{ old('phone_no', $user->phone_no) }}" class="w-full bg-dark-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-gold-500 outline-none transition-all">
                                    </div>
                                </div>

                                <div class="mt-8 pt-6 border-t border-white/5">
                                    <h4 class="text-white font-medium mb-4">Change Password (Leave blank to keep current)</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-sm text-gray-400 uppercase tracking-widest font-bold">Current Password</label>
                                            <input type="password" name="current_password" class="w-full bg-dark-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-gold-500 outline-none transition-all">
                                        </div>
                                        <div class="space-y-2 hidden md:block"></div>
                                        <div class="space-y-2">
                                            <label class="text-sm text-gray-400 uppercase tracking-widest font-bold">New Password</label>
                                            <input type="password" name="new_password" class="w-full bg-dark-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-gold-500 outline-none transition-all">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm text-gray-400 uppercase tracking-widest font-bold">Confirm New Password</label>
                                            <input type="password" name="new_password_confirmation" class="w-full bg-dark-800 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-gold-500 outline-none transition-all">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-8 py-3 rounded-lg transition-all">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-red-500/5 rounded-xl border border-red-500/20 p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div>
                            <h3 class="text-red-400 font-serif text-lg mb-1">Delete Account</h3>
                            <p class="text-gray-500 text-sm">Once you delete your account, there is no going back. Please be certain.</p>
                        </div>
                        <button class="px-6 py-2 border border-red-500/50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all text-sm font-bold">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
