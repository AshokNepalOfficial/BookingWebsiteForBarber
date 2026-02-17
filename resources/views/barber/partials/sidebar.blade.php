<!-- MOBILE BACKDROP OVERLAY -->
<div id="mobile-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/80 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

<!-- SIDEBAR -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-dark-900 border-r border-white/10 flex flex-col h-full transition-transform duration-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto">
    <!-- Logo Area -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-white/5 shrink-0">
        <div class="flex items-center">
            @if($setting('logo_admin_dark'))
            <img src="{{ asset('storage/' . $setting('logo_admin_dark')) }}" alt="Barber Logo" class="h-auto px-4 object-contain">
            @else
            <div class="w-8 h-8 bg-gold-500 rounded-full flex items-center justify-center text-dark-900 mr-3">
                <i class="fas fa-cut"></i>
            </div>
            <span class="font-serif text-xl font-bold">Barber</span>
            @endif
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-4 space-y-1 overflow-y-auto custom-scroll">
        <p class="px-6 text-xs font-bold text-gray-600 uppercase tracking-widest mt-4 mb-2">Main</p>
        <a href="{{ route('barber.dashboard') }}" class="nav-link {{ request()->routeIs('barber.dashboard') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-chart-line w-6 text-center"></i> <span class="ml-2">My Schedule</span>
        </a>
        <a href="{{ route('barber.bookings.index') }}" class="nav-link {{ request()->routeIs('barber.bookings.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="far fa-calendar-check w-6 text-center"></i> <span class="ml-2">My Bookings</span>
        </a>
        
        <p class="px-6 text-xs font-bold text-gray-600 uppercase tracking-widest mt-6 mb-2">Quick Links</p>
        <a href="{{ url('/') }}" target="_blank" class="nav-link flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-external-link-alt w-6 text-center"></i> <span class="ml-2">Visit Site</span>
        </a>
    </nav>

    <div class="p-4 border-t border-white/5 bg-dark-900 shrink-0">
        <div class="flex items-center gap-3">
            @auth
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name) }}&background=EAB308&color=000" class="w-10 h-10 rounded-full">
            <div class="overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->first_name }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-gray-500 hover:text-gold-500">Log Out</button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</aside>
