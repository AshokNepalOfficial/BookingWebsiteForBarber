<!-- Header -->
<header class="bg-dark-900/80 backdrop-blur-md sticky top-0 z-20 border-b border-white/5 px-4 sm:px-8 py-4 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="lg:hidden text-white text-xl"><i class="fas fa-bars"></i></button>
        <h2 class="text-xl md:text-2xl font-serif text-white truncate">@yield('page-title', 'Admin Panel')</h2>
    </div>
    <div class="flex items-center gap-3 sm:gap-4">
        @yield('header-actions')
    </div>
</header>
