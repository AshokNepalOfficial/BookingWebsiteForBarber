<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($setting('site_name') ?? 'JB Barber Unisex Salon') . ' | ' . ($setting('site_tagline') ?? 'Luxury Barbershop'))</title>
    
    @if($setting('favicon_light'))
    <link rel="icon" type="image/png" href="{{ asset('storage/' . $setting('favicon_light')) }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/png" href="{{ asset('storage/' . ($setting('favicon_dark') ?: $setting('favicon_light'))) }}" media="(prefers-color-scheme: dark)">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom Styles */
        body { font-family: 'Inter', sans-serif; }
        
        .logo-icon {
            width: 40px; height: 40px;
            background-color: #EAB308;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #111; font-size: 1.2rem;
        }

        /* Booking Card Selected State */
        .service-card.selected {
            border-color: #EAB308;
            background-color: #FEFCE8;
            box-shadow: 0 4px 6px -1px rgba(234, 179, 8, 0.2);
        }
        .service-card.selected .check-icon {
            background-color: #EAB308;
            border-color: #EAB308;
            color: black;
        }

        /* Transitions */
        .glass-nav {
            background: rgba(17, 17, 17, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        /* Animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-up { animation: fadeUp 0.8s ease-out forwards; opacity: 0; }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
        /* Toast Notification */
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideOutRight {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100%); }
        }
        .toast-notification {
            animation: slideInRight 0.3s ease-out;
        }
        .toast-notification.hide {
            animation: slideOutRight 0.3s ease-out;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-dark-900 text-white overflow-x-hidden">

















    @include('partials.frontend-navbar_custom_2')

    @yield('content')

    @include('partials.frontend-footer')



    @include('partials.booking-modal')

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-50 space-y-3"></div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // --- Toast Notification ---
        function showToast(message, type = 'success', duration = 5000) {
            const toast = document.createElement('div');
            const colors = {
                success: 'bg-green-500 border-green-400',
                error: 'bg-red-500 border-red-400',
                info: 'bg-blue-500 border-blue-400',
                warning: 'bg-yellow-500 border-yellow-400'
            };
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                info: 'fa-info-circle',
                warning: 'fa-exclamation-triangle'
            };
            
            toast.className = `toast-notification ${colors[type]} border-2 text-white px-6 py-4 rounded-lg shadow-2xl max-w-md`;
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <i class="fas ${icons[type]} text-2xl mt-0.5 flex-shrink-0"></i>
                    <div class="flex-1">
                        <p class="font-bold mb-1">${type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : 'Notice'}</p>
                        <div class="text-sm">${message}</div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white/80 hover:text-white ml-2 flex-shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.getElementById('toast-container').appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
        
        // Show session messages
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        @if(session('info'))
            showToast('{{ session('info') }}', 'info');
        @endif
        
        // --- Navbar Effect ---
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('glass-nav');
            } else {
                navbar.classList.remove('glass-nav');
            }
        });

        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>
</html>
