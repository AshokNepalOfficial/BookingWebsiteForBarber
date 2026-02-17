<!-- NAVIGATION -->
<nav class="w-full z-40 transition-all duration-300 py-2" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="font-serif text-2xl font-bold text-white tracking-wide">
                    @if($setting('logo_frontend_light'))
                        <img class="h-16 object-contain" src="{{ asset('storage/' . $setting('logo_frontend_light')) }}" alt="{{ $setting('site_name') ?? 'Logo' }}">
                    @else
                        <img class="h-16" src="{{ asset('whitelogo.png') }}" alt="Logo">
                    @endif
                </a>
            </div>

            <!-- Desktop Menu Links -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}#home" class="text-gold-500 text-xs font-medium tracking-widest">HOME</a>
                <a href="{{ route('home') }}#services" class="text-gray-300 hover:text-white text-xs font-medium tracking-widest transition-colors">SERVICES</a>
                <a href="{{ route('home') }}#team" class="text-gray-300 hover:text-white text-xs font-medium tracking-widest transition-colors">TEAM</a>
                <a href="{{ route('gallery') }}" class="text-gray-300 hover:text-white text-xs font-medium tracking-widest transition-colors">GALLERY</a>
                <a href="{{ route('home') }}#reviews" class="text-gray-300 hover:text-white text-xs font-medium tracking-widest transition-colors">REVIEWS</a>
            </div>

            <!-- Desktop Auth / Buttons -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('profile.dashboard') }}" class="text-white hover:text-gold-500 text-xs font-medium tracking-widest transition-colors">
                            <i class="fas fa-user-circle mr-1"></i> PROFILE
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-gold-500 text-xs font-medium tracking-widest transition-colors">
                            <i class="fas fa-user-circle mr-1"></i> DASHBOARD
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-gold-500 text-xs font-medium tracking-widest transition-colors border border-white/20 hover:border-gold-500 px-4 py-2 rounded-sm">
                        LOGIN
                    </a>
                    <a href="{{ route('register') }}" class="bg-white hover:bg-gray-200 text-dark-900 font-bold text-xs py-2 px-4 rounded-sm transition-all tracking-widest">
                        REGISTER
                    </a>
                @endauth

                @php
                    $onProfile = request()->is('profile*'); // matches /profile and all subpaths
                @endphp

                <button 
                    @if(!$onProfile)
                        onclick="openBooking()"
                    @else
                        onclick="window.location='{{ url('/') }}'"
                    @endif
                    class="bg-gold-500 hover:bg-gold-400 text-dark-900 font-bold text-xs py-2 px-6 rounded-sm transition-all shadow-lg hover:shadow-gold-500/20"
                >
                    {{ $onProfile ? 'VISIT SITE' : 'BOOK NOW' }}
                </button>
            </div>

            <!-- Mobile Menu Toggle -->
            <button onclick="toggleMobileMenu()" class="md:hidden text-white text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden bg-dark-900 border-t md:hidden border-gray-800 w-full">
        <div class="flex flex-col p-4 space-y-4 text-center">
            <a href="{{ route('home') }}#home" class="text-gold-500 font-medium">HOME</a>
            <a href="{{ route('home') }}#services" class="text-gray-300">SERVICES</a>
            <a href="{{ route('home') }}#team" class="text-gray-300">TEAM</a>
            <a href="{{ route('gallery') }}" class="text-gray-300">GALLERY</a>
            <a href="{{ route('home') }}#reviews" class="text-gray-300">REVIEWS</a>

            @auth
                @if(auth()->user()->role === 'customer')
                    <a href="{{ url('/profile') }}" class="text-white py-2 font-medium">
                        <i class="fas fa-user-circle mr-1"></i> PROFILE
                    </a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="text-white py-2 font-medium">
                        <i class="fas fa-user-circle mr-1"></i> DASHBOARD
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="border border-white/20 text-white py-2 font-medium">LOGIN</a>
                <a href="{{ route('register') }}" class="bg-white text-dark-900 py-2 font-bold">REGISTER</a>
            @endauth

            <button 
                @if(!$onProfile)
                    onclick="openBooking()"
                @else
                    onclick="window.location='{{ url('/') }}'"
                @endif
                class="bg-gold-500 text-dark-900 py-2 font-bold"
            >
                {{ $onProfile ? 'VISIT SITE' : 'BOOK NOW' }}
            </button>
        </div>
    </div>
</nav>
