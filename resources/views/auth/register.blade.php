<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register | {{ $setting('site_name') ?? "JB Barber Unisex Salon" }}</title>
    
    @if($setting('favicon_light'))
    <link rel="icon" type="image/png" href="{{ asset('storage/' . $setting('favicon_light')) }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/png" href="{{ asset('storage/' . ($setting('favicon_dark') ?: $setting('favicon_light'))) }}" media="(prefers-color-scheme: dark)">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-dark-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-4">
                @if($setting('logo_auth_dark'))
                <img src="{{ asset('storage/' . $setting('logo_auth_dark')) }}" alt="{{ $setting('site_name') ?? 'Logo' }}" class="h-12 object-contain">
                @else
                <div class="w-12 h-12 bg-gold-500 rounded-full flex items-center justify-center text-dark-900">
                    <i class="fas fa-cut text-2xl"></i>
                </div>
                <span class="font-serif text-3xl font-bold text-white">{{ $setting('site_name') ?? "JB Barber Unisex Salon" }}</span>
                @endif
            </div>
            <p class="text-gray-400">Create your account</p>
        </div>

        <!-- Register Form -->
        <div class="bg-dark-800 rounded-lg border border-white/10 p-8">
            @if($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded mb-6">
                <ul class="text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                               class="w-full bg-dark-900 border border-white/10 text-white px-4 py-3 rounded focus:outline-none focus:border-gold-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                               class="w-full bg-dark-900 border border-white/10 text-white px-4 py-3 rounded focus:outline-none focus:border-gold-500 transition-colors">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full bg-dark-900 border border-white/10 text-white px-4 py-3 rounded focus:outline-none focus:border-gold-500 transition-colors">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2">Phone Number</label>
                    <input type="text" name="phone_no" value="{{ old('phone_no') }}" required 
                           placeholder="+1 (555) 123-4567"
                           class="w-full bg-dark-900 border border-white/10 text-white px-4 py-3 rounded focus:outline-none focus:border-gold-500 transition-colors">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full bg-dark-900 border border-white/10 text-white px-4 py-3 rounded focus:outline-none focus:border-gold-500 transition-colors">
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-400 text-sm mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required 
                           class="w-full bg-dark-900 border border-white/10 text-white px-4 py-3 rounded focus:outline-none focus:border-gold-500 transition-colors">
                </div>

                <button type="submit" class="w-full bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold py-3 rounded transition-colors">
                    Create Account
                </button>
            </form>

            <div class="mt-6 text-center text-gray-400 text-sm">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-gold-500 hover:text-gold-400 font-medium">Sign in</a>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-white text-sm transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Home
            </a>
        </div>
    </div>
</body>
</html>
