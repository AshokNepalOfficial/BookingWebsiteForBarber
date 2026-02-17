<!-- ========================================== -->
<!--      MULTI-STEP BOOKING MODAL SYSTEM       -->
<!-- ========================================== -->

<div id="bookingModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/90 backdrop-blur-md transition-opacity"></div>
    
    <!-- Modal Container -->
    <div class="relative w-full h-full md:h-auto md:max-w-5xl md:mx-auto md:my-10 flex flex-col md:rounded-lg overflow-hidden bg-cream-50 text-gray-800 shadow-2xl">
        
        <!-- Modal Header -->
        <div class="bg-dark-900 text-white px-6 py-4 flex justify-between items-center border-b border-white/10 shrink-0">
            <div class="flex items-center gap-2">
                <i class="fas fa-cut text-gold-500"></i>
                <span class="font-serif text-xl tracking-wide">Book Your Visit</span>
            </div>
            <button onclick="closeBooking()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white px-6 py-6 border-b border-gray-100 shrink-0">
            <div class="flex justify-center items-center max-w-xl mx-auto">
                <div class="flex items-center relative">
                    <div id="step-dot-1" class="w-8 h-8 rounded-full bg-dark-900 text-gold-500 font-bold flex items-center justify-center text-sm z-10 transition-colors">1</div>
                    <div class="absolute top-8 w-max -left-2 text-[10px] font-bold uppercase tracking-widest text-dark-900">Services</div>
                </div>
                <div id="step-line-1" class="w-16 md:w-24 h-0.5 bg-gray-200 mx-2 transition-colors"></div>
                
                <div class="flex items-center relative">
                    <div id="step-dot-2" class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-bold flex items-center justify-center text-sm z-10 transition-colors">2</div>
                    <div class="absolute top-8 w-max -left-1 text-[10px] font-bold uppercase tracking-widest text-gray-400">Barber</div>
                </div>
                <div id="step-line-2" class="w-16 md:w-24 h-0.5 bg-gray-200 mx-2 transition-colors"></div>
                
                <div class="flex items-center relative">
                    <div id="step-dot-3" class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-bold flex items-center justify-center text-sm z-10 transition-colors">3</div>
                    <div class="absolute top-8 w-max -left-1 text-[10px] font-bold uppercase tracking-widest text-gray-400">Time</div>
                </div>
                <div id="step-line-3" class="w-16 md:w-24 h-0.5 bg-gray-200 mx-2 transition-colors"></div>
                
                <div class="flex items-center relative">
                    <div id="step-dot-4" class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-bold flex items-center justify-center text-sm z-10 transition-colors">4</div>
                    <div class="absolute top-8 w-max -left-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">Details</div>
                </div>
                <div id="step-line-4" class="w-16 md:w-24 h-0.5 bg-gray-200 mx-2 transition-colors"></div>
                
                <div class="flex items-center relative">
                    <div id="step-dot-5" class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-bold flex items-center justify-center text-sm z-10 transition-colors">5</div>
                    <div class="absolute top-8 w-max -left-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">Confirm</div>
                </div>
            </div>
        </div>

        <!-- Scrollable Content Area -->
        <div class="flex-1 overflow-y-auto p-6 md:p-10 bg-gray-50 relative">
            
            <!-- STEP 1: SELECT MULTIPLE SERVICES -->
            <div id="step-1" class="animate-up">
                <h2 class="font-serif text-3xl text-dark-900 text-center mb-2">Select Services</h2>
                <p class="text-center text-gray-500 text-sm mb-8">Choose one or more treatments.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="services-container">
                    @foreach($services ?? [] as $service)
                    <div onclick="toggleService(this, '{{ $service->title }}', {{ $service->price }}, {{ $service->duration_minutes }}, {{ $service->id }})" 
                         class="service-card cursor-pointer bg-white p-5 rounded border border-gray-200 hover:border-gold-500 transition-all flex justify-between items-center group relative overflow-hidden">
                        <div class="flex-1">
                            <h3 class="font-serif text-lg font-bold text-dark-900">{{ $service->title }}</h3>
                            <p class="text-gray-500 text-xs mt-1">{{ $service->duration_minutes }} min • {{ $service->sub_title }}</p>
                        </div>
                        <div class="text-right mx-4">
                            <span class="font-bold text-lg text-dark-900">{!! $formatPrice($service->price) !!}</span>
                        </div>
                        <div class="check-icon w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center text-xs text-transparent transition-all">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- STEP 2: SELECT BARBER -->
            <div id="step-2" class="hidden animate-up">
                <h2 class="font-serif text-3xl text-dark-900 text-center mb-2">Select Professional</h2>
                <p class="text-center text-gray-500 text-sm mb-8">Choose your preferred barber.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div onclick="selectBarber(this, 'Any Professional', null)" class="barber-card cursor-pointer bg-white p-6 rounded-lg border-2 border-gray-200 hover:border-gold-500 transition-all text-center group">
                        <div class="w-20 h-20 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center text-3xl text-gray-400">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="font-bold text-dark-900">Any Available</h3>
                        <p class="text-xs text-gray-500">Earliest Availability</p>
                    </div>

                    @foreach($barbers ?? [] as $barber)
                    <div onclick="selectBarber(this, '{{ $barber->first_name }} {{ $barber->last_name }}', {{ $barber->id }})" class="barber-card cursor-pointer bg-white p-6 rounded-lg border-2 border-gray-200 hover:border-gold-500 transition-all text-center group">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($barber->first_name . ' ' . $barber->last_name) }}&background=EAB308&color=000" 
                             class="w-20 h-20 rounded-full mx-auto mb-4 object-cover grayscale group-hover:grayscale-0 transition-all">
                        <h3 class="font-bold text-dark-900">{{ $barber->first_name }} {{ $barber->last_name }}</h3>
                        <p class="text-xs text-gold-600 font-bold uppercase">{{ ucfirst($barber->role) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- STEP 3: DATE & TIME -->
            <div id="step-3" class="hidden animate-up">
                <h2 class="font-serif text-3xl text-dark-900 text-center mb-8">Select Date & Time</h2>
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Date Picker -->
                    <div class="flex-1 bg-white p-6 rounded border border-gray-200">
                        <label class="font-bold mb-4 block">Select Date</label>
                        <input type="date" id="appointment-date" 
                               class="w-full border border-gray-300 rounded p-3 focus:border-gold-500 focus:outline-none"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               max="{{ date('Y-m-d', strtotime('+' . ($bookingSettings()['max_days_advance'] ?? 15) . ' days')) }}">
                    </div>
                    <!-- Times -->
                    <div class="flex-1 bg-white p-6 rounded border border-gray-200">
                        <h4 class="font-bold mb-4">Available Slots</h4>
                        <div class="grid grid-cols-3 gap-3" id="time-slots-container">
                            @php
                                $timeSlots = $bookingSettings()['time_slots'] ?? [
                                    '09:00', '10:00', '11:00', '12:00', '13:00', '14:00',
                                    '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'
                                ];
                            @endphp
                            @foreach($timeSlots as $slot)
                            <button onclick="selectTime(this, '{{ $slot }}')" class="time-btn border border-gray-200 py-2 rounded text-sm hover:border-gold-500 hover:text-gold-600 transition-colors">{{ date('h:i A', strtotime($slot)) }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: GUEST INFORMATION (Only for non-logged in users) -->
            <div id="step-4" class="hidden animate-up">
                <h2 class="font-serif text-3xl text-dark-900 text-center mb-2">Your Information</h2>
                <p class="text-center text-gray-500 text-sm mb-8">Please provide your contact details.</p>
                
                <div class="max-w-2xl mx-auto bg-white p-8 rounded border border-gray-200">
                    @guest
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" id="guest-first-name" placeholder="Enter first name" required class="border border-gray-300 p-3 rounded w-full focus:border-gold-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" id="guest-last-name" placeholder="Enter last name" required class="border border-gray-300 p-3 rounded w-full focus:border-gold-500 focus:outline-none">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" id="guest-email" placeholder="your@email.com" required class="border border-gray-300 p-3 rounded w-full focus:border-gold-500 focus:outline-none">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" id="guest-phone" placeholder="+1 234 567 8900" required class="border border-gray-300 p-3 rounded w-full focus:border-gold-500 focus:outline-none">
                    </div>
                    @else
                    <div class="mb-4 p-6 bg-gray-50 rounded border text-center">
                        <i class="fas fa-user-check text-4xl text-green-500 mb-3"></i>
                        <p class="text-sm text-gray-600 mb-2">Booking as:</p>
                        <p class="font-bold text-xl text-dark-900">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->phone_no }}</p>
                    </div>
                    @endguest
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests (Optional)</label>
                        <textarea id="guest-special-request" placeholder="Any special requests or notes..." class="border border-gray-300 p-3 rounded w-full h-24 focus:border-gold-500 focus:outline-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- STEP 5: CONFIRMATION -->
            <div id="step-5" class="hidden animate-up">
            <!-- STEP 5: CONFIRMATION -->
            <div id="step-5" class="hidden animate-up">
                <h2 class="font-serif text-3xl text-dark-900 text-center mb-8">Confirm Your Booking</h2>
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Summary -->
                    <div class="md:w-1/2 bg-dark-900 text-white p-6 rounded h-fit">
                        <h3 class="font-serif text-xl text-gold-500 border-b border-white/10 pb-2 mb-4">Booking Summary</h3>
                        <div id="summary-list" class="space-y-2 mb-4 text-sm text-gray-300">
                            <!-- JS Injected Items -->
                        </div>
                        <div class="border-t border-white/10 pt-2 mb-2">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Total Duration</span>
                                <span id="summary-duration">0 min</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-xl font-bold text-gold-500 mb-6">
                            <span>Total</span>
                            <span id="summary-total">$0</span>
                        </div>
                        <div class="space-y-3 pt-4 border-t border-white/10 text-sm">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user-tie text-gold-500"></i>
                                <div>
                                    <p class="text-gray-500 text-xs">Barber</p>
                                    <p id="summary-barber" class="text-white font-semibold">...</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-day text-gold-500"></i>
                                <div>
                                    <p class="text-gray-500 text-xs">Appointment</p>
                                    <p id="summary-date-time" class="text-white font-semibold">...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confirmation Message -->
                    <div class="md:w-1/2 bg-white p-6 rounded border border-gray-200">
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
                            <h3 class="text-2xl font-bold text-dark-900 mb-2">Almost There!</h3>
                            <p class="text-gray-600 mb-6">Review your booking details and confirm to complete your reservation.</p>
                            
                            <div class="bg-gray-50 p-4 rounded text-left text-sm text-gray-600">
                                <p class="mb-2"><i class="fas fa-info-circle text-gold-500 mr-2"></i> We'll send you a confirmation email shortly.</p>
                                <p><i class="fas fa-phone text-gold-500 mr-2"></i> We may contact you to confirm your appointment.</p>
                            </div>
                        </div>
                        
                        <!-- Hidden Form -->
                        <form id="booking-form" action="{{ route('booking.store') }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="service_ids" id="hidden-service-ids">
                            <input type="hidden" name="barber_id" id="hidden-barber-id">
                            <input type="hidden" name="appointment_date" id="hidden-appointment-date">
                            <input type="hidden" name="appointment_time" id="hidden-appointment-time">
                            <input type="hidden" name="first_name" id="hidden-first-name">
                            <input type="hidden" name="last_name" id="hidden-last-name">
                            <input type="hidden" name="email" id="hidden-email">
                            <input type="hidden" name="phone_no" id="hidden-phone-no">
                            <input type="hidden" name="special_request" id="hidden-special-request">
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Controls -->
        <div class="bg-white p-6 border-t border-gray-100 flex justify-between items-center shrink-0">
            <button id="prevBtn" onclick="navigateStep(-1)" class="px-6 py-3 rounded text-gray-500 hover:text-dark-900 font-medium invisible">
                Back
            </button>
            <div class="text-right">
                <p id="footer-total" class="text-xs text-gray-400 uppercase tracking-widest mb-1 hidden">Total: $0</p>
                <button id="nextBtn" onclick="navigateStep(1)" class="px-8 py-3 bg-dark-900 text-white font-bold rounded hover:bg-gold-500 hover:text-black transition-all">
                    Select Barber
                </button>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // --- Booking System State ---
    const IS_LOGGED_IN = {{ Auth::check() ? 'true' : 'false' }};
    const ENABLE_BARBER_SELECTION = {{ $bookingSettings()['enable_barber_selection'] == '1' ? 'true' : 'false' }};
    
    // Calculate dynamic steps based on settings
    let stepMapping = [];
    let currentStepIndex = 0;
    
    function initializeSteps() {
        stepMapping = [1]; // Step 1: Services (always present)
        
        if (ENABLE_BARBER_SELECTION) {
            stepMapping.push(2); // Step 2: Barber selection
        }
        
        stepMapping.push(3); // Step 3: Date & Time (always present)
        
        if (!IS_LOGGED_IN) {
            stepMapping.push(4); // Step 4: Guest info (only if not logged in)
        }
        
        stepMapping.push(5); // Step 5: Confirmation (always present)
        
        return stepMapping.length;
    }
    
    const totalSteps = initializeSteps();
    
    let bookingData = {
        services: [],
        barber: 'Any Available',
        barber_id: null,
        date: null,
        time: null,
        firstName: IS_LOGGED_IN ? '{{ Auth::user()->first_name ?? "" }}' : '',
        lastName: IS_LOGGED_IN ? '{{ Auth::user()->last_name ?? "" }}' : '',
        email: IS_LOGGED_IN ? '{{ Auth::user()->email ?? "" }}' : '',
        phone: IS_LOGGED_IN ? '{{ Auth::user()->phone_no ?? "" }}' : '',
        specialRequest: ''
    };

    // --- Functions ---

    function openBooking() {
        document.getElementById('bookingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeBooking() {
        document.getElementById('bookingModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Toggle Service (Multiple Selection)
    function toggleService(card, name, price, duration, id) {
        const index = bookingData.services.findIndex(s => s.id === id);
        
        if (index > -1) {
            bookingData.services.splice(index, 1);
            card.classList.remove('selected');
        } else {
            bookingData.services.push({ name, price, duration, id });
            card.classList.add('selected');
        }
        updateFooterTotal();
    }

    function selectBarber(card, name, id) {
        document.querySelectorAll('.barber-card').forEach(c => {
            c.classList.remove('border-gold-500', 'bg-gold-50');
            c.classList.add('border-gray-200', 'bg-white');
        });
        card.classList.remove('border-gray-200', 'bg-white');
        card.classList.add('border-gold-500', 'bg-gold-50');
        
        bookingData.barber = name;
        bookingData.barber_id = id;
    }

    function selectTime(btn, time) {
        document.querySelectorAll('.time-btn').forEach(b => {
            b.classList.remove('bg-gold-500', 'text-white', 'border-gold-500');
            b.classList.add('border-gray-200', 'text-gray-800');
        });
        btn.classList.remove('border-gray-200', 'text-gray-800');
        btn.classList.add('bg-gold-500', 'text-white', 'border-gold-500');
        bookingData.time = time;
    }

    function updateFooterTotal() {
        const total = bookingData.services.reduce((acc, curr) => acc + parseFloat(curr.price), 0);
        const footerTotal = document.getElementById('footer-total');
        if(total > 0) {
            footerTotal.classList.remove('hidden');
            footerTotal.innerText = `Estimated Total: $${total.toFixed(2)}`;
        } else {
            footerTotal.classList.add('hidden');
        }
    }

    function navigateStep(direction) {
        const currentPhysicalStep = stepMapping[currentStepIndex];
        
        // Validation before moving forward
        if (direction === 1) {
            // Step 1: Services validation
            if (currentPhysicalStep === 1 && bookingData.services.length === 0) {
                showToast("Please select at least one service.", 'warning');
                return;
            }
            
            // Step 2: Barber validation (only if enabled)
            if (currentPhysicalStep === 2 && !bookingData.barber) {
                showToast("Please select a barber.", 'warning');
                return;
            }
            
            // Step 3: Date & Time validation
            if (currentPhysicalStep === 3) {
                const dateInput = document.getElementById('appointment-date');
                bookingData.date = dateInput.value;
                
                if (!bookingData.date) {
                    showToast("Please select a date.", 'warning');
                    return;
                }
                if (!bookingData.time) {
                    showToast("Please select a time.", 'warning');
                    return;
                }
            }
            
            // Step 4: Guest info validation (only if not logged in)
            if (currentPhysicalStep === 4 && !IS_LOGGED_IN) {
                bookingData.firstName = document.getElementById('guest-first-name').value.trim();
                bookingData.lastName = document.getElementById('guest-last-name').value.trim();
                bookingData.email = document.getElementById('guest-email').value.trim();
                bookingData.phone = document.getElementById('guest-phone').value.trim();
                bookingData.specialRequest = document.getElementById('guest-special-request').value.trim();
                
                if (!bookingData.firstName || !bookingData.lastName || !bookingData.email || !bookingData.phone) {
                    showToast("Please fill in all required fields.", 'error');
                    return;
                }
            }
            
            // Step 5: Submit booking
            if (currentPhysicalStep === 5) {
                submitBooking();
                return;
            }
        }

        // Calculate next step index
        let nextStepIndex = currentStepIndex + direction;
        
        if (nextStepIndex >= 0 && nextStepIndex < totalSteps) {
            // Hide current step
            document.getElementById(`step-${currentPhysicalStep}`).classList.add('hidden');
            
            // Show next step
            const nextPhysicalStep = stepMapping[nextStepIndex];
            document.getElementById(`step-${nextPhysicalStep}`).classList.remove('hidden');
            
            // Update progress
            updateProgressDots(nextStepIndex + 1);
            
            // Update buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            prevBtn.style.visibility = nextStepIndex === 0 ? 'hidden' : 'visible';
            
            // Dynamic button text based on actual steps
            updateButtonText(nextStepIndex, nextBtn);
            
            currentStepIndex = nextStepIndex;
        }
    }
    
    function updateButtonText(stepIndex, nextBtn) {
        const physicalStep = stepMapping[stepIndex];
        
        if (physicalStep === 1) {
            nextBtn.innerText = ENABLE_BARBER_SELECTION ? "Select Barber" : "Select Date & Time";
        } else if (physicalStep === 2) {
            nextBtn.innerText = "Select Date & Time";
        } else if (physicalStep === 3) {
            nextBtn.innerText = IS_LOGGED_IN ? "Review Booking" : "Continue";
        } else if (physicalStep === 4) {
            nextBtn.innerText = "Review Booking";
        } else if (physicalStep === 5) {
            renderSummary();
            nextBtn.innerText = "Confirm Booking";
        }
    }

    function updateProgressDots(visualStep) {
        // Update dots based on visual progression (not physical step numbers)
        for(let i = 1; i <= 5; i++) {
            const dot = document.getElementById(`step-dot-${i}`);
            const line = document.getElementById(`step-line-${i}`);
            
            if (dot && i <= visualStep) {
                dot.classList.remove('bg-gray-200', 'text-gray-500');
                dot.classList.add('bg-dark-900', 'text-gold-500');
                if(line && i < visualStep) {
                    line.classList.remove('bg-gray-200');
                    line.classList.add('bg-gold-500');
                }
            } else if (dot) {
                dot.classList.remove('bg-dark-900', 'text-gold-500');
                dot.classList.add('bg-gray-200', 'text-gray-500');
                if(line) {
                    line.classList.remove('bg-gold-500');
                    line.classList.add('bg-gray-200');
                }
            }
        }
    }

    function renderSummary() {
        const listContainer = document.getElementById('summary-list');
        listContainer.innerHTML = '';
        
        let totalCost = 0;
        let totalTime = 0;

        bookingData.services.forEach(s => {
            totalCost += parseFloat(s.price);
            totalTime += parseInt(s.duration);
            listContainer.innerHTML += `
                <div class="flex justify-between">
                    <span>${s.name}</span>
                    <span>$${parseFloat(s.price).toFixed(2)}</span>
                </div>
            `;
        });

        document.getElementById('summary-total').innerText = `$${totalCost.toFixed(2)}`;
        document.getElementById('summary-duration').innerText = `${totalTime} Mins`;
        document.getElementById('summary-barber').innerText = bookingData.barber;
        document.getElementById('summary-date-time').innerText = `${bookingData.date} @ ${bookingData.time}`;
        
        // Populate hidden form fields
        document.getElementById('hidden-service-ids').value = JSON.stringify(bookingData.services.map(s => s.id));
        document.getElementById('hidden-barber-id').value = bookingData.barber_id;
        document.getElementById('hidden-appointment-date').value = bookingData.date;
        document.getElementById('hidden-appointment-time').value = bookingData.time;
        document.getElementById('hidden-first-name').value = bookingData.firstName;
        document.getElementById('hidden-last-name').value = bookingData.lastName;
        document.getElementById('hidden-email').value = bookingData.email;
        document.getElementById('hidden-phone-no').value = bookingData.phone;
        document.getElementById('hidden-special-request').value = bookingData.specialRequest;
    }

    function submitBooking() {
        const form = document.getElementById('booking-form');
        const formData = new FormData(form);
        
        // Add hidden fields to FormData
        formData.set('service_ids', document.getElementById('hidden-service-ids').value);
        formData.set('barber_id', document.getElementById('hidden-barber-id').value);
        formData.set('appointment_date', document.getElementById('hidden-appointment-date').value);
        formData.set('appointment_time', document.getElementById('hidden-appointment-time').value);
        formData.set('first_name', document.getElementById('hidden-first-name').value);
        formData.set('last_name', document.getElementById('hidden-last-name').value);
        formData.set('email', document.getElementById('hidden-email').value);
        formData.set('phone_no', document.getElementById('hidden-phone-no').value);
        formData.set('special_request', document.getElementById('hidden-special-request').value);
        
        // Disable confirm button to prevent double submission
        const nextBtn = document.getElementById('nextBtn');
        const originalText = nextBtn.innerText;
        nextBtn.disabled = true;
        nextBtn.innerText = 'Processing...';
        
        // Submit via fetch to handle response
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(async response => {
            const data = await response.json();
            return { ok: response.ok, status: response.status, data };
        })
        .then(({ ok, status, data }) => {
            // Re-enable button
            nextBtn.disabled = false;
            nextBtn.innerText = originalText;
            
            if (ok && data.success) {
                closeBooking();
                showToast(data.message || 'Booking created successfully! We will contact you soon.', 'success', 6000);
                // Reset form and booking data
                form.reset();
                bookingData = {
                    services: [],
                    barber: 'Any Available',
                    barber_id: null,
                    date: null,
                    time: null,
                    firstName: IS_LOGGED_IN ? '{{ Auth::user()->first_name ?? "" }}' : '',
                    lastName: IS_LOGGED_IN ? '{{ Auth::user()->last_name ?? "" }}' : '',
                    email: IS_LOGGED_IN ? '{{ Auth::user()->email ?? "" }}' : '',
                    phone: IS_LOGGED_IN ? '{{ Auth::user()->phone_no ?? "" }}' : '',
                    specialRequest: ''
                };
                currentStepIndex = 0;
                document.getElementById('step-5').classList.add('hidden');
                document.getElementById('step-1').classList.remove('hidden');
                updateProgressDots(1);
                
                // Clear all selections
                document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
                document.querySelectorAll('.time-btn').forEach(b => {
                    b.classList.remove('bg-gold-500', 'text-white', 'border-gold-500');
                    b.classList.add('border-gray-200', 'text-gray-800');
                });
            } else if (status === 422 && data.errors) {
                // Validation errors
                const errorMessages = Object.values(data.errors).flat().join('<br>');
                showToast(errorMessages, 'error', 8000);
            } else {
                showToast(data.message || 'Something went wrong. Please try again.', 'error');
            }
        })
        .catch(error => {
            // Re-enable button
            nextBtn.disabled = false;
            nextBtn.innerText = originalText;
            
            console.error('Error:', error);
            showToast('Failed to submit booking. Please check your information and try again.', 'error');
        });
    }
</script>
@endpush
