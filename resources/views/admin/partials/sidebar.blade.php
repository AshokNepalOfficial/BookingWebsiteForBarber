<!-- MOBILE BACKDROP OVERLAY -->
<div id="mobile-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/80 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

<!-- SIDEBAR -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-dark-900 border-r border-white/10 flex flex-col h-full transition-transform duration-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto">
    <!-- Logo Area -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-white/5 shrink-0">
        <div class="flex items-center">
            @if($setting('logo_admin_dark'))
            <img src="{{ asset('storage/' . $setting('logo_admin_dark')) }}" alt="Admin Logo" class="h-auto px-4 object-contain">
            @else
            <div class="w-8 h-8 bg-gold-500 rounded-full flex items-center justify-center text-dark-900 mr-3">
                <i class="fas fa-cut"></i>
            </div>
            <span class="font-serif text-xl font-bold">Admin</span>
            @endif
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-4 space-y-1 overflow-y-auto custom-scroll">
        <p class="px-6 text-xs font-bold text-gray-600 uppercase tracking-widest mt-4 mb-2">Main</p>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-chart-line w-6 text-center"></i> <span class="ml-2">Dashboard</span>
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="far fa-calendar-check w-6 text-center"></i> <span class="ml-2">Bookings</span>
            @if(isset($pendingCount) && $pendingCount > 0)
            <span class="ml-auto bg-gold-500 text-dark-900 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
            @endif
        </a>
        
        @if(in_array(Auth::user()->role, ['admin', 'manager']))
        <p class="px-6 text-xs font-bold text-gray-600 uppercase tracking-widest mt-6 mb-2">Inventory</p>
        <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-list w-6 text-center"></i> <span class="ml-2">Services</span>
        </a>
        <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-users w-6 text-center"></i> <span class="ml-2">Customers</span>
        </a>
        <a href="{{ route('admin.memberships.index') }}" class="nav-link {{ request()->routeIs('admin.memberships.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-crown w-6 text-center"></i> <span class="ml-2">Memberships</span>
        </a>
        @endif

        @if(Auth::user()->role === 'admin')
        <p class="px-6 text-xs font-bold text-gray-600 uppercase tracking-widest mt-6 mb-2">Team</p>
        <a href="{{ route('admin.staff.index') }}" class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-user-tie w-6 text-center"></i> <span class="ml-2">Staff</span>
        </a>
        <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-user-shield w-6 text-center"></i> <span class="ml-2">Roles & Permissions</span>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'manager']))
        <p class="px-6 text-xs font-bold text-gray-600 uppercase tracking-widest mt-6 mb-2">Financial</p>
        <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-dollar-sign w-6 text-center"></i> <span class="ml-2">Transactions</span>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-chart-bar w-6 text-center"></i> <span class="ml-2">Reports</span>
        </a>
        @endif

        @if(Auth::user()->role === 'admin')
        <p class="px-6 text-xs font-bold text-gray-600 uppercase tracking-widest mt-6 mb-2">Website</p>
        <a href="{{ route('admin.cms.index') }}" class="nav-link {{ request()->routeIs('admin.cms.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-edit w-6 text-center"></i> <span class="ml-2">CMS Content</span>
        </a>
        <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-file-alt w-6 text-center"></i> <span class="ml-2">Pages</span>
        </a>
        <a href="{{ route('admin.blogs.index') }}" class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-blog w-6 text-center"></i> <span class="ml-2">Blog</span>
        </a>
        <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-images w-6 text-center"></i> <span class="ml-2">Gallery</span>
        </a>
        <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-quote-left w-6 text-center"></i> <span class="ml-2">Testimonials</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex items-center px-6 py-3 text-sm font-medium hover:bg-dark-800 hover:text-white text-gray-400">
            <i class="fas fa-cog w-6 text-center"></i> <span class="ml-2">Settings</span>
        </a>
        @endif
    </nav>

    <div class="p-4 border-t border-white/5 bg-dark-900 shrink-0">
        <div class="flex items-center gap-3">
            @auth
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name) }}&background=EAB308&color=000" class="w-10 h-10 rounded-full">
            <div class="overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->first_name }}</p>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-gray-500 hover:text-gold-500">Log Out</button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</aside>
