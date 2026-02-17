<!-- Instagram CTA -->
<section class="bg-dark-900 py-24 text-center border-b border-white/5 relative z-10">
    <h2 class="font-serif text-4xl text-white mb-6">Follow Us on Instagram</h2>
    <p class="text-gray-400 mb-10 max-w-xl mx-auto text-sm md:text-base">See more of our work and stay updated with the latest styles and trends.</p>
    <a href="#" class="inline-block bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold py-3 px-10 rounded-sm transition-colors shadow-lg hover:shadow-gold-500/20">
        @jbunisexsalon
    </a>
</section>

<!-- Main Footer Content -->
<footer id="contact" class="bg-dark-900 pt-20 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
            
            <!-- Brand Column -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <a href="{{ route('home') }}" class="font-serif text-2xl font-bold text-white tracking-wide">
                        @if($setting('logo_frontend_light'))
                        <img class="h-16 object-contain" src="{{ asset('storage/' . $setting('logo_frontend_light')) }}" alt="{{ $setting('site_name') ?? 'Logo' }}">
                        @else
                        <img class="h-16" src="{{ asset('whitelogo.png') }}" alt="Logo">
                        @endif
                    </a>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed pr-4">
                    {{ $setting('site_tagline') ?? 'Experience the art of traditional barbering with a modern touch. Where every cut is crafted to perfection.' }}
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-serif text-gold-500 text-lg mb-6">Quick Links</h4>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li><a href="#services" class="hover:text-white transition-colors">Services & Pricing</a></li>
                    <li><a href="#team" class="hover:text-white transition-colors">Our Barbers</a></li>
                    <li><a href="#gallery" class="hover:text-white transition-colors">Gallery</a></li>
                    <li><button onclick="openBooking()" class="hover:text-white transition-colors text-left">Book Appointment</button></li>
                </ul>
            </div>

            <!-- Contact Us -->
            <div>
                <h4 class="font-serif text-gold-500 text-lg mb-6">Contact Us</h4>
                <ul class="space-y-6 text-sm text-gray-400">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-gold-500 mt-1"></i>
                        <span>{{ $setting('contact_address') ?? '123 Main Street, Downtown, New York, NY 10001' }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone-alt text-gold-500"></i>
                        <span>{{ $setting('contact_phone') ?? '(555) 123-4567' }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-gold-500"></i>
                        <span>{{ $setting('contact_email') ?? 'info@jbunisexsalon.com' }}</span>
                    </li>
                </ul>
            </div>

            <!-- Hours & Social -->
            <div>
                <h4 class="font-serif text-gold-500 text-lg mb-6">Hours</h4>
                <ul class="space-y-3 text-sm text-gray-400 mb-8">
                    <li class="flex justify-between max-w-[200px]"><span>Mon - Fri:</span> <span>9AM - 8PM</span></li>
                    <li class="flex justify-between max-w-[200px]"><span>Saturday:</span> <span>9AM - 6PM</span></li>
                    <li class="flex justify-between max-w-[200px]"><span>Sunday:</span> <span>10AM - 4PM</span></li>
                </ul>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-dark-800 border border-white/10 flex items-center justify-center text-gold-500 hover:bg-gold-500 hover:text-dark-900 hover:border-gold-500 transition-all">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-dark-800 border border-white/10 flex items-center justify-center text-gold-500 hover:bg-gold-500 hover:text-dark-900 hover:border-gold-500 transition-all">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-white/10 pt-8 text-center text-gray-600 text-sm">
            &copy; {{ date('Y') }} {{ $setting('site_name') ?? "JB Barber Unisex Salon" }}. All rights reserved.
        </div>
    </div>
</footer>
