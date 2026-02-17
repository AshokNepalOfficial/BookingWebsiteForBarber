<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | {{ $setting('site_name') ?? "JB Barber Unisex Salon" }}</title>
    
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
            <p class="text-gray-400">Sign in to your account</p>
        </div>

        <!-- Login Form -->
        <div class="bg-dark-800 rounded-lg border border-white/10 p-8">
            @if($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded mb-6">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full bg-dark-900 border border-white/10 text-white px-4 py-3 rounded focus:outline-none focus:border-gold-500 transition-colors">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-400 text-sm mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full bg-dark-900 border border-white/10 text-white px-4 py-3 rounded focus:outline-none focus:border-gold-500 transition-colors">
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-gray-400 text-sm">
                        <input type="checkbox" name="remember" class="mr-2 rounded bg-dark-900 border-white/10">
                        Remember me
                    </label>
                    <a href="#" class="text-gold-500 text-sm hover:text-gold-400">Forgot password?</a>
                </div>

                <button type="submit" class="w-full bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold py-3 rounded transition-colors">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center text-gray-400 text-sm">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-gold-500 hover:text-gold-400 font-medium">Sign up</a>
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
