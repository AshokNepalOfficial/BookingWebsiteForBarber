<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($setting('site_name') ?? 'JB Barber Unisex Salon') . ' | ' . ($setting('site_tagline') ?? 'Luxury Barbershop'))</title>


@php
$hairSalonJson = [
    "@context" => "https://schema.org",
    "@type" => "HairSalon",
    "name" => "Justin Barber Unisex Salon",
    "image" => "https://jbunisexsalon.com/storage/branding/6d489Y273uJGdeUrGBlgALviAyiUeuJA6Fh2THz1.png",
    "telephone" => "+9779847494308",
    "address" => [
        "@type" => "PostalAddress",
        "streetAddress" => "Dhapakhel Rd",
        "addressLocality" => "Lalitpur",
        "postalCode" => "44700",
        "addressCountry" => "NP"
    ],
    "url" => "https://jbunisexsalon.com/",
    "sameAs" => [
        "https://m.me/102832891734622",
        "https://wa.me/9779847494308",
        "viber://chat?number=9779847494308"
    ],
    "openingHours" => "Mo-Su 07:00-20:00",
    "priceRange" => "$$",
    "servesCuisine" => "Haircuts, Beard Grooming, Hair Coloring"
];
@endphp

<script type="application/ld+json">
{!! json_encode($hairSalonJson, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>


    
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






<!-- FontAwesome (Required for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- ========================================== -->
<!--      FLOATING SOCIAL MEDIA POPUP           -->
<!-- ========================================== -->
<div class="fixed bottom-6 right-6 z-[999] flex flex-col items-center gap-3">
    
    <!-- Social Buttons Container -->
    <div id="social-buttons" class="flex flex-col gap-3 transition-all duration-300 transform translate-y-10 opacity-0 invisible origin-bottom">
        
        <!-- 1. FACEBOOK MESSENGER (Updated with your ID) -->
        <a href="https://m.me/102832891734622?ref=Hello%2C%20I%20am%20interested%20in%20JB%20Unisex%20Salon%20services%20like%20haircuts%2C%20beard%20trims%2C%20and%20more." target="_blank" class="w-12 h-12 rounded-full bg-[#0084FF] text-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform relative group">
            <i class="fab fa-facebook-messenger text-2xl"></i>
            <!-- Tooltip -->
            <span class="absolute right-14 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Messenger</span>
        </a>

                
        <!-- 2. WHATSAPP -->
        <a href="https://wa.me/9779847494308?text=Hello%2C%20I%20am%20interested%20in%20JB%20Unisex%20Salon%20services%20like%20haircuts%2C%20beard%20trims%2C%20and%20more." target="_blank" class="w-12 h-12 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform relative group">
            <i class="fab fa-whatsapp text-2xl"></i>
            <span class="absolute right-14 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">WhatsApp</span>
        </a>

        <!-- 3. VIBER -->
        <a href="viber://chat?number=%2B9779847494308&text=Hello%2C%20I%20am%20interested%20in%20JB%20Unisex%20Salon%20services%20like%20haircuts%2C%20beard%20trims%2C%20and%20more." class="w-12 h-12 rounded-full bg-[#665CAC] text-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform relative group">
            <i class="fab fa-viber text-2xl"></i>
            <span class="absolute right-14 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Viber</span>
        </a>

    </div>



    <button onclick="toggleFab()" id="fab-toggle" class="w-14 h-14 rounded-full bg-gold-500 hover:bg-gold-600 rounded-full text-dark-900 shadow-lg flex items-center justify-center shadow-xl transition-colors focus:outline-none relative z-50">
      <!-- Icons handled by JS -->
            <i class="fas fa-comment-dots text-2xl transition-all duration-300 transform" id="fab-icon-open"></i>
            <i class="fas fa-times text-2xl transition-all duration-300 transform opacity-0 absolute rotate-90" id="fab-icon-close"></i>
    </button>
</div>
   <script>
        // --- NEW FAB TOGGLE LOGIC ---
        function toggleFab() {
            const buttons = document.getElementById('social-buttons');
            const iconOpen = document.getElementById('fab-icon-open');
            const iconClose = document.getElementById('fab-icon-close');

            if (buttons.classList.contains('invisible')) {
                // Open State
                buttons.classList.remove('invisible', 'opacity-0', 'translate-y-10');
                
                // Icon Animation: Hide Chat, Show X
                iconOpen.classList.add('opacity-0', 'rotate-90');
                iconClose.classList.remove('opacity-0', 'rotate-90'); // Reset rotate to 0 effectively
                iconClose.classList.add('rotate-0');
            } else {
                // Close State
                buttons.classList.add('invisible', 'opacity-0', 'translate-y-10');
                
                // Icon Animation: Show Chat, Hide X
                iconOpen.classList.remove('opacity-0', 'rotate-90');
                iconClose.classList.remove('rotate-0');
                iconClose.classList.add('opacity-0', 'rotate-90');
            }
        }
        
        // Helper CSS
        const style = document.createElement('style');
        style.innerHTML = `@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } } .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }`;
        document.head.appendChild(style);

    </script>







<script>
  window.addEventListener("load", function () {
    if (window.location.hash === "#openBookNowForm") {
      openBooking();
    }
  });
</script>




    @include('partials.frontend-navbar')

    @yield('content')

    @include('partials.frontend-footer')

    @include('partials.booking-modal')

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-50 space-y-3"></div>





    
    <!-- FLOATING SOCIAL MEDIA BUTTON -->
    <div class="fixed bottom-6 right-6 z-50 group">
        <!-- Popup Menu -->
        <div id="social-menu" class="absolute bottom-16 right-0 w-48 bg-dark-800 border border-gold-500/30 rounded-lg shadow-2xl transform scale-90 opacity-0 invisible transition-all duration-200 origin-bottom-right">
            <div class="p-4 space-y-3">
                <h4 class="text-xs text-gray-500 uppercase tracking-widest mb-2 border-b border-white/10 pb-2">Connect</h4>
                <a href="#" class="flex items-center gap-3 text-gray-300 hover:text-gold-500 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-dark-900 flex items-center justify-center border border-white/10"><i class="fab fa-instagram"></i></div>
                    <span class="text-sm">Instagram</span>
                </a>
                <a href="#" class="flex items-center gap-3 text-gray-300 hover:text-gold-500 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-dark-900 flex items-center justify-center border border-white/10"><i class="fab fa-facebook-f"></i></div>
                    <span class="text-sm">Facebook</span>
                </a>
                <a href="#" class="flex items-center gap-3 text-gray-300 hover:text-gold-500 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-dark-900 flex items-center justify-center border border-white/10"><i class="fab fa-twitter"></i></div>
                    <span class="text-sm">Twitter</span>
                </a>
            </div>
        </div>

<!-- Main Toggle Button (Light Purple/Lavender) -->
        <button onclick="toggleFab()" id="fab-toggle" class="w-14 h-14 rounded-full bg-[#9F7AEA] text-white flex items-center justify-center shadow-xl hover:bg-[#8B5CF6] transition-colors focus:outline-none relative group">
            <!-- Icons handled by JS -->
            <i class="fas fa-comment-dots text-2xl transition-all duration-300 transform " id="fab-icon-open"></i>
            
        </button>
    </div>

    <!-- JS LOGIC -->
    <script>
        // Social Menu Toggle
        function toggleSocialMenu() {
            const menu = document.getElementById('social-menu');
            if (menu.classList.contains('invisible')) {
                menu.classList.remove('invisible', 'opacity-0', 'scale-90');
                menu.classList.add('opacity-100', 'scale-100');
            } else {
                menu.classList.add('invisible', 'opacity-0', 'scale-90');
                menu.classList.remove('opacity-100', 'scale-100');
            }
        }
        
        // CSS Animation helper
        const style = document.createElement('style');
        style.innerHTML = `@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } } .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }`;
        document.head.appendChild(style);

    </script>
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
