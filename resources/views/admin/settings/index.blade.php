@extends('layouts.admin')
@section('title', 'Settings | Admin Panel')
@section('page-title', 'System Settings')

@section('content')

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="space-y-6">
        <!-- Branding Settings -->
        @if(isset($settings['branding']))
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h2 class="font-serif text-2xl text-white mb-6 flex items-center gap-2">
                <i class="fas fa-palette text-gold-500"></i> Branding Settings
            </h2>

            <!-- Logo Settings -->
            <div class="mb-8">
                <h3 class="text-lg text-gray-300 mb-4 font-medium">Logos & Branding</h3>
                
                <!-- Frontend Logos -->
                <div class="mb-6">
                    <h4 class="text-md text-gray-400 mb-3 font-medium">Frontend (Navbar & Footer)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($settings['branding']->whereIn('key', ['logo_frontend_light', 'logo_frontend_dark']) as $setting)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">
                                {{ ucwords(str_replace(['logo_frontend_', '_'], ['', ' '], $setting->key)) }} Logo
                            </label>
                            
                            @if($setting->value)
                            <div class="mb-3 p-4 bg-dark-800 rounded-lg border border-white/5">
                                <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->key }}" class="max-h-20 object-contain">
                            </div>
                            @endif
                            
                            <input type="file" name="settings[{{ $setting->key }}]" accept="image/*" class="input-dark">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Recommended: PNG with transparent background, max height 64px
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Admin Panel Logos -->
                <div class="mb-6">
                    <h4 class="text-md text-gray-400 mb-3 font-medium">Admin Panel (Sidebar)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($settings['branding']->whereIn('key', ['logo_admin_light', 'logo_admin_dark']) as $setting)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">
                                {{ ucwords(str_replace(['logo_admin_', '_'], ['', ' '], $setting->key)) }} Logo
                            </label>
                            
                            @if($setting->value)
                            <div class="mb-3 p-4 bg-dark-800 rounded-lg border border-white/5">
                                <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->key }}" class="max-h-20 object-contain">
                            </div>
                            @endif
                            
                            <input type="file" name="settings[{{ $setting->key }}]" accept="image/*" class="input-dark">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Recommended: PNG with transparent background, max height 48px
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Auth Pages Logos -->
                <div class="mb-6">
                    <h4 class="text-md text-gray-400 mb-3 font-medium">Authentication Pages (Login & Register)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($settings['branding']->whereIn('key', ['logo_auth_light', 'logo_auth_dark']) as $setting)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">
                                {{ ucwords(str_replace(['logo_auth_', '_'], ['', ' '], $setting->key)) }} Logo
                            </label>
                            
                            @if($setting->value)
                            <div class="mb-3 p-4 bg-dark-800 rounded-lg border border-white/5">
                                <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->key }}" class="max-h-20 object-contain">
                            </div>
                            @endif
                            
                            <input type="file" name="settings[{{ $setting->key }}]" accept="image/*" class="input-dark">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Recommended: PNG with transparent background, max height 48px
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Favicons -->
                <div>
                    <h4 class="text-md text-gray-400 mb-3 font-medium">Favicons (Browser Tab Icon)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($settings['branding']->whereIn('key', ['favicon_light', 'favicon_dark']) as $setting)
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">
                                {{ ucwords(str_replace(['favicon_', '_'], ['', ' '], $setting->key)) }} Favicon
                            </label>
                            
                            @if($setting->value)
                            <div class="mb-3 p-4 bg-dark-800 rounded-lg border border-white/5">
                                <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->key }}" class="max-h-16 object-contain">
                            </div>
                            @endif
                            
                            <input type="file" name="settings[{{ $setting->key }}]" accept="image/*" class="input-dark">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Recommended: 32x32px or 64x64px, PNG/ICO format
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Typography Settings -->
            <div class="mb-8 pt-6 border-t border-white/10">
                <h3 class="text-lg text-gray-300 mb-4 font-medium">Typography</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($settings['branding']->filter(function($setting) { return str_starts_with($setting->key, 'font'); }) as $setting)
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                        </label>
                        @if($setting->key == 'font_size_base')
                        <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="input-dark" min="12" max="24">
                        <p class="text-xs text-gray-500 mt-1">Base font size in pixels</p>
                        @else
                        <select name="settings[{{ $setting->key }}]" class="input-dark">
                            <option value="Inter" {{ $setting->value == 'Inter' ? 'selected' : '' }}>Inter</option>
                            <option value="Roboto" {{ $setting->value == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                            <option value="Open Sans" {{ $setting->value == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                            <option value="Lato" {{ $setting->value == 'Lato' ? 'selected' : '' }}>Lato</option>
                            <option value="Montserrat" {{ $setting->value == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                            <option value="Playfair Display" {{ $setting->value == 'Playfair Display' ? 'selected' : '' }}>Playfair Display</option>
                            <option value="Merriweather" {{ $setting->value == 'Merriweather' ? 'selected' : '' }}>Merriweather</option>
                            <option value="Poppins" {{ $setting->value == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                            <option value="Raleway" {{ $setting->value == 'Raleway' ? 'selected' : '' }}>Raleway</option>
                            <option value="Nunito" {{ $setting->value == 'Nunito' ? 'selected' : '' }}>Nunito</option>
                        </select>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Color Settings -->
            <div class="pt-6 border-t border-white/10">
                <h3 class="text-lg text-gray-300 mb-4 font-medium">Color Scheme</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($settings['branding']->filter(function($setting) { return str_starts_with($setting->key, 'color'); }) as $setting)
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                        </label>
                        <div class="flex gap-3 items-center">
                            <input type="color" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="w-16 h-12 rounded border border-white/10 cursor-pointer bg-transparent">
                            <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="input-dark flex-1" pattern="#[0-9A-Fa-f]{6}" placeholder="#000000">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Choose color or enter hex code</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Currency Settings -->
        @if(isset($settings['currency']))
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h2 class="font-serif text-2xl text-white mb-6 flex items-center gap-2">
                <i class="fas fa-dollar-sign text-gold-500"></i> Currency Settings
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($settings['currency'] as $setting)
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                    </label>
                    @if($setting->key == 'currency_position')
                    <select name="settings[{{ $setting->key }}]" class="input-dark">
                        <option value="before" {{ $setting->value == 'before' ? 'selected' : '' }}>Before ($100)</option>
                        <option value="after" {{ $setting->value == 'after' ? 'selected' : '' }}>After (100$)</option>
                    </select>
                    @else
                    <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="input-dark">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- General Settings -->
        @if(isset($settings['general']))
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h2 class="font-serif text-2xl text-white mb-6 flex items-center gap-2">
                <i class="fas fa-cog text-gold-500"></i> General Settings
            </h2>
            <div class="space-y-4">
                @foreach($settings['general'] as $setting)
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                    </label>
                    <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="input-dark">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Booking Settings -->
        @if(isset($settings['booking']))
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h2 class="font-serif text-2xl text-white mb-6 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-gold-500"></i> Booking Settings
            </h2>
            
            <div class="space-y-6">
                <!-- Basic Booking Settings -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($settings['booking']->whereNotIn('key', ['enable_barber_selection', 'booking_time_slots']) as $setting)
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                        </label>
                        <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="input-dark">
                        @if($setting->key == 'booking_max_days_advance')
                        <p class="text-xs text-gray-500 mt-1">Maximum days customers can book in advance</p>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Barber Selection Toggle -->
                @php
                    $barberSetting = $settings['booking']->firstWhere('key', 'enable_barber_selection');
                @endphp
                @if($barberSetting)
                <div class="pt-6 border-t border-white/10">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="settings[{{ $barberSetting->key }}]" value="1" 
                               {{ $barberSetting->value == '1' ? 'checked' : '' }}
                               class="w-5 h-5 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                        <div>
                            <span class="text-white font-medium">Enable Barber Selection</span>
                            <p class="text-xs text-gray-500">Allow customers to select a specific barber during booking. If disabled, "Any Available" will be the default.</p>
                        </div>
                    </label>
                </div>
                @endif

                <!-- Custom Time Slots -->
                @php
                    $timeSetting = $settings['booking']->firstWhere('key', 'booking_time_slots');
                    $timeSlots = $timeSetting ? json_decode($timeSetting->value, true) : [];
                @endphp
                @if($timeSetting)
                <div class="pt-6 border-t border-white/10">
                    <h3 class="text-lg text-gray-300 mb-4 font-medium">Available Time Slots</h3>
                    <p class="text-sm text-gray-400 mb-4">Add or remove time slots for customer bookings</p>
                    
                    <div id="time-slots-container" class="space-y-2 mb-4">
                        @foreach($timeSlots as $index => $slot)
                        <div class="flex items-center gap-2 time-slot-row">
                            <input type="time" name="settings[booking_time_slots][]" value="{{ $slot }}" class="input-dark flex-1">
                            <button type="button" onclick="removeTimeSlot(this)" class="bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-2 rounded transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    
                    <button type="button" onclick="addTimeSlot()" class="bg-gold-500/10 hover:bg-gold-500/20 border border-gold-500/30 text-gold-400 px-4 py-2 rounded transition-colors">
                        <i class="fas fa-plus mr-2"></i> Add Time Slot
                    </button>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Contact Settings -->
        @if(isset($settings['contact']))
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h2 class="font-serif text-2xl text-white mb-6 flex items-center gap-2">
                <i class="fas fa-address-book text-gold-500"></i> Contact Settings
            </h2>
            <div class="space-y-4">
                @foreach($settings['contact'] as $setting)
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                    </label>
                    @if($setting->key == 'contact_address')
                    <textarea name="settings[{{ $setting->key }}]" rows="2" class="input-dark">{{ $setting->value }}</textarea>
                    @else
                    <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="input-dark">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Save Button -->
    <div class="mt-6 flex justify-end">
        <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-8 py-3 rounded transition-colors">
            <i class="fas fa-save mr-2"></i> Save All Settings
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
function addTimeSlot() {
    const container = document.getElementById('time-slots-container');
    const newRow = document.createElement('div');
    newRow.className = 'flex items-center gap-2 time-slot-row';
    newRow.innerHTML = `
        <input type="time" name="settings[booking_time_slots][]" value="09:00" class="input-dark flex-1">
        <button type="button" onclick="removeTimeSlot(this)" class="bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-2 rounded transition-colors">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newRow);
}

function removeTimeSlot(button) {
    button.closest('.time-slot-row').remove();
}
</script>
@endpush
