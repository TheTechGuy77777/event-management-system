@extends('layouts.dashboard')

@section('title', 'Edit Event — ' . $event->name)
@section('page-title', 'Edit Event')
@section('page-subtitle', $event->name)

@section('content')

    <!-- Back -->
    <div class="mb-6">
        <a href="{{ route('dashboard.events.index') }}"
            class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors text-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to My Events
        </a>
    </div>

    <!-- Draft Only Notice -->
    <div class="glass-gold rounded-2xl p-4 mb-6 flex items-center gap-3">
        <i class="fa-solid fa-circle-info text-amber-400 flex-shrink-0"></i>
        <p class="text-amber-400 text-sm">
            You can only edit events that are in <strong>Draft</strong> status.
            Once published, only minor details can be changed.
        </p>
    </div>

    <form method="POST" action="{{ route('dashboard.events.update', $event) }}" enctype="multipart/form-data"
        x-data="editWizard()">
        @csrf
        @method('PATCH')

        <div class="space-y-6">

            <!-- Event Details -->
            <div class="glass rounded-2xl p-8">
                <h2 class="text-white font-semibold text-lg mb-6 flex items-center gap-3">
                    <div class="w-8 h-8 gold-gradient rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-calendar text-black text-xs"></i>
                    </div>
                    Event Details
                </h2>

                <div class="space-y-6">

                    <!-- Event Name -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Event Name <span class="text-amber-400">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $event->name) }}" maxlength="75" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Description <span class="text-amber-400">*</span>
                        </label>
                        <textarea name="description" rows="5" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all resize-none">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <!-- Type & Category -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-400 text-sm font-medium mb-2 block">
                                Event Format <span class="text-amber-400">*</span>
                            </label>
                            <select name="event_type" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                                @foreach (['Party', 'Performance', 'Concert', 'Festival', 'Exhibition', 'Screening', 'Market', 'Pop Up', 'Conference', 'Class', 'Workshop', 'Presentation', 'Meetup', 'Attraction', 'Tournament', 'Networking'] as $type)
                                    <option value="{{ $type }}"
                                        {{ old('event_type', $event->event_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-gray-400 text-sm font-medium mb-2 block">
                                Category <span class="text-amber-400">*</span>
                            </label>
                            <select name="category_id" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Location</label>

                        <div class="flex items-center gap-3 mb-4 p-4 glass rounded-xl cursor-pointer"
                            x-on:click="isVirtual = !isVirtual">
                            <div class="w-10 h-6 rounded-full transition-colors duration-300 relative flex-shrink-0"
                                :class="isVirtual ? 'bg-amber-400' : 'bg-white/10'">
                                <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-transform duration-300"
                                    :class="isVirtual ? 'translate-x-5' : 'translate-x-1'"></div>
                            </div>
                            <p class="text-white text-sm font-medium">This is a virtual event</p>
                            <input type="hidden" name="is_virtual" :value="isVirtual ? '1' : '0'">
                        </div>

                        <div x-show="!isVirtual" x-transition class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <select name="country"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                                @foreach (['Nigeria', 'Ghana', 'Kenya', 'South Africa', 'Rwanda', 'United Kingdom'] as $country)
                                    <option value="{{ $country }}"
                                        {{ old('country', $event->country) == $country ? 'selected' : '' }}>
                                        {{ $country }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="location" value="{{ old('location', $event->location) }}"
                                placeholder="Venue name and address"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                        </div>

                        <div x-show="isVirtual" x-transition>
                            <input type="text" name="virtual_link"
                                value="{{ old('virtual_link', $event->virtual_link) }}"
                                placeholder="https://zoom.us/j/your-meeting-link"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-500 text-xs mb-1 block">Start Date & Time</label>
                            <input type="datetime-local" name="start_date"
                                value="{{ old('start_date', $event->start_date?->format('Y-m-d\TH:i')) }}" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                        </div>
                        <div>
                            <label class="text-gray-500 text-xs mb-1 block">End Date & Time</label>
                            <input type="datetime-local" name="end_date"
                                value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i')) }}" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                        </div>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label class="text-gray-500 text-xs mb-1 block">Timezone</label>
                        <select name="timezone"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                            @foreach ([
            'Africa/Lagos' => 'West Africa Time (WAT) — Lagos, Abuja',
            'Africa/Accra' => 'Ghana Mean Time (GMT) — Accra',
            'Africa/Nairobi' => 'East Africa Time (EAT) — Nairobi',
            'Africa/Johannesburg' => 'South Africa Standard Time — Johannesburg',
            'Africa/Kigali' => 'Central Africa Time — Kigali',
            'Europe/London' => 'Greenwich Mean Time (GMT) — London',
            'UTC' => 'UTC',
        ] as $tz => $label)
                                <option value="{{ $tz }}"
                                    {{ old('timezone', $event->timezone) == $tz ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom URL -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Event URL Slug</label>
                        <div
                            class="flex items-center glass rounded-xl overflow-hidden border border-white/10 focus-within:border-amber-400/50 transition-all">
                            <span
                                class="text-gray-500 text-sm px-4 py-3 border-r border-white/10 bg-white/2 whitespace-nowrap">
                                eventplug.test/events/
                            </span>
                            <input type="text" name="slug" value="{{ old('slug', $event->slug) }}"
                                class="flex-1 bg-transparent px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none">
                        </div>
                    </div>

                    <!-- Cover Image -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Cover Image</label>
                        @if ($event->cover_image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $event->cover_image) }}"
                                    class="h-32 rounded-xl object-cover" alt="Current cover">
                                <p class="text-gray-500 text-xs mt-1">Current cover image</p>
                            </div>
                        @endif
                        <input type="file" name="cover_image" accept="image/jpeg,image/png"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-400 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                        <p class="text-gray-600 text-xs mt-1">Upload a new image to replace the current one. JPEG or PNG,
                            max 2MB.</p>
                    </div>

                    <!-- Social Links -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-3 block">
                            Social Links <span class="text-gray-600 font-normal">(optional)</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="relative">
                                <i
                                    class="fa-brands fa-instagram absolute left-4 top-1/2 -translate-y-1/2 text-pink-400 text-sm"></i>
                                <input type="text" name="instagram" value="{{ old('instagram', $event->instagram) }}"
                                    placeholder="@yourhandle"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                            </div>
                            <div class="relative">
                                <i
                                    class="fa-brands fa-x-twitter absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" name="twitter" value="{{ old('twitter', $event->twitter) }}"
                                    placeholder="@yourhandle"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                            </div>
                            <div class="relative">
                                <i
                                    class="fa-brands fa-facebook absolute left-4 top-1/2 -translate-y-1/2 text-blue-400 text-sm"></i>
                                <input type="text" name="facebook" value="{{ old('facebook', $event->facebook) }}"
                                    placeholder="Facebook event link"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                            </div>
                            <div class="relative">
                                <i
                                    class="fa-solid fa-globe absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" name="website" value="{{ old('website', $event->website) }}"
                                    placeholder="https://yourwebsite.com"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Model -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-3 block">
                            Commission Model <span class="text-amber-400">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_model" value="attendee_pays"
                                    {{ old('payment_model', $event->payment_model) == 'attendee_pays' ? 'checked' : '' }}
                                    class="hidden peer">
                                <div
                                    class="glass rounded-xl p-4 border-2 border-transparent peer-checked:border-amber-400/50 peer-checked:bg-amber-400/5 transition-all">
                                    <p class="text-white text-sm font-semibold mb-1">Attendee Pays</p>
                                    <p class="text-gray-500 text-xs">Commission added on top of ticket price.</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_model" value="manager_pays"
                                    {{ old('payment_model', $event->payment_model) == 'manager_pays' ? 'checked' : '' }}
                                    class="hidden peer">
                                <div
                                    class="glass rounded-xl p-4 border-2 border-transparent peer-checked:border-amber-400/50 peer-checked:bg-amber-400/5 transition-all">
                                    <p class="text-white text-sm font-semibold mb-1">I Pay Later</p>
                                    <p class="text-gray-500 text-xs">Commission deducted from your payout.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-between mb-16">
                <a href="{{ route('dashboard.events.index') }}"
                    class="btn-outline-gold px-8 py-3 rounded-xl font-semibold text-sm">
                    Cancel
                </a>
                <button type="submit" class="btn-gold px-8 py-3 rounded-xl text-black font-semibold text-sm">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        function editWizard() {
            return {
                isVirtual: {{ $event->is_virtual ? 'true' : 'false' }},
            }
        }
    </script>
@endpush
