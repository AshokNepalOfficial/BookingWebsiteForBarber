<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal | ' . ($setting('site_name') ?? 'JB Barber Unisex Salon'))</title>
    
    @if($setting('favicon_light'))
    <link rel="icon" type="image/png" href="{{ asset('storage/' . $setting('favicon_light')) }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/png" href="{{ asset('storage/' . ($setting('favicon_dark') ?: $setting('favicon_light'))) }}" media="(prefers-color-scheme: dark)">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #111; color: white; }
        
        /* Status Badges */
        .badge { padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        .badge-pending { background: rgba(234, 179, 8, 0.15); color: #fbbf24; border: 1px solid rgba(234, 179, 8, 0.3); }
        .badge-confirmed { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); }
        .badge-completed { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .badge-cancelled { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .badge-rejected { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .badge-new { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .badge-verified { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); }
        
        /* Sidebar Link Active State */
        .nav-link.active { background-color: #1A1A1A; border-left: 3px solid #EAB308; color: #EAB308; }
        .nav-link { border-left: 3px solid transparent; transition: all 0.2s; }
        
        /* Smooth Fade */
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Toast Slide Animation */
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in { animation: slideIn 0.3s ease-out; transition: all 0.3s ease-out; }

        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
        .custom-scroll::-webkit-scrollbar-track { background: #1a1a1a; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #EAB308; }

        /* Form Inputs */
        .input-dark { background-color: #111; border: 1px solid #333; color: white; padding: 10px; border-radius: 4px; width: 100%; transition: border-color 0.2s; }
        .input-dark:focus { outline: none; border-color: #EAB308; }
    </style>
    
    @stack('styles')
</head>
<body class="h-screen overflow-hidden flex bg-dark-900">

    @include('admin.partials.sidebar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 bg-dark-800 h-full overflow-y-auto w-full relative">
        
        @include('admin.partials.header')

        <!-- Content Padding -->
        <div class="p-4 sm:p-8">
            <!-- Flash Messages -->
            @if(session('success'))
            <div id="successAlert" class="mb-6 bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-xl"></i>
                    <p>{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div id="errorAlert" class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 px-6 py-4 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                    <p>{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div id="validationAlert" class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 px-6 py-4 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                        <p class="font-bold">Please fix the following errors:</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-red-400 hover:text-red-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <ul class="list-disc list-inside ml-8 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('mobile-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        // Notification sound
        function playNotificationSound() {
            const audio = new Audio('/sounds/notification.mp3');
            audio.volume = 0.5;
            audio.play().catch(err => console.log('Audio play failed:', err));
        }

        // Toast notification
        function showToast(message, type = 'info', duration = 5000) {
            const toast = document.createElement('div');
            const colors = {
                success: 'bg-green-500/90 border-green-400',
                error: 'bg-red-500/90 border-red-400',
                info: 'bg-blue-500/90 border-blue-400',
                warning: 'bg-yellow-500/90 border-yellow-400'
            };
            
            toast.className = `fixed top-20 right-4 ${colors[type]} border text-white px-6 py-4 rounded-lg shadow-2xl z-50 animate-slide-in max-w-md`;
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} text-xl mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-semibold mb-1">New Booking Alert</p>
                        <p class="text-sm">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white/80 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            const validationAlert = document.getElementById('validationAlert');
            
            if (successAlert) successAlert.remove();
            if (errorAlert) errorAlert.remove();
            if (validationAlert) validationAlert.remove();
        }, 5000);
    </script>

    @auth
    <script>
        // Real-time booking notifications
        if (typeof Echo !== 'undefined') {
            Echo.private('staff-notifications')
                .listen('.booking.created', (data) => {
                    console.log('New booking received:', data);
                    
                    // Play notification sound
                    playNotificationSound();
                    
                    // Show toast notification
                    const message = `${data.customer} booked ${data.services.join(', ')} on ${data.date} at ${data.time}`;
                    showToast(message, 'info', 8000);
                    
                    // Update notification badge if exists
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        const count = parseInt(badge.textContent || '0');
                        badge.textContent = count + 1;
                        badge.classList.remove('hidden');
                    }
                });
        }
    </script>
    @endauth

    @stack('scripts')
</body>
</html>
