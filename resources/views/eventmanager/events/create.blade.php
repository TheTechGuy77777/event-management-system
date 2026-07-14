@extends('layouts.dashboard')

@section('title', 'Create Event — EventPlug')
@section('page-title', 'Create New Event')
@section('page-subtitle', 'Fill in the details to get your event live')

@section('content')

    <div x-data="eventWizard()" class="max-w-4xl mx-auto">

        <!-- Toast Notification -->
        <div x-show="errorMessage" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-[-20px]" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-[-20px]"
            class="fixed top-6 right-6 z-50 flex items-center gap-3 bg-[#1a1a1a] border border-red-500/30 text-red-400 px-5 py-4 rounded-2xl shadow-2xl max-w-sm"
            style="display:none;">
            <i class="fa-solid fa-circle-exclamation text-lg flex-shrink-0"></i>
            <p class="text-sm font-medium" x-text="errorMessage"></p>
            <button x-on:click="errorMessage = ''"
                class="ml-2 text-red-400/50 hover:text-red-400 transition-colors flex-shrink-0">
                <i class="fa-solid fa-times text-xs"></i>
            </button>
        </div>

        <!-- Step Indicator -->
        <div class="glass rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between relative">

                <!-- Progress Line -->
                <div class="absolute left-0 right-0 top-5 h-px bg-white/5 z-0 mx-16"></div>
                <div class="absolute left-16 top-5 h-px bg-amber-400/50 z-0 transition-all duration-500"
                    :style="'width: calc(' + ((currentStep - 1) / 2 * 100) + '% - 0px)'"></div>

                <!-- Steps -->
                @foreach ([['num' => 1, 'label' => 'Event Details', 'icon' => 'fa-calendar'], ['num' => 2, 'label' => 'Cover Image', 'icon' => 'fa-image'], ['num' => 3, 'label' => 'Tickets', 'icon' => 'fa-ticket']] as $step)
                    <div class="flex flex-col items-center gap-2 relative z-10">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold transition-all duration-300"
                            :class="{
                                'gold-gradient text-black': currentStep >= {{ $step['num'] }},
                                'glass text-gray-500': currentStep < {{ $step['num'] }}
                            }">
                            <i class="fa-solid {{ $step['icon'] }}"></i>
                        </div>
                        <span class="text-xs font-medium transition-colors duration-300"
                            :class="currentStep >= {{ $step['num'] }} ? 'text-amber-400' : 'text-gray-600'">
                            {{ $step['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>



        <!-- Form -->

        <form method="POST" action="{{ route('dashboard.events.store') }}" enctype="multipart/form-data" id="event-form">
            @csrf
            <input type="hidden" name="action" id="action-input" value="draft">

            <!-- Validation Error -->
            <div x-show="errorMessage" x-transition
                class="bg-red-500/10 border border-red-500/20 rounded-2xl p-4 mb-6 flex items-center gap-3 text-red-400 text-sm">
                <i class="fa-solid fa-circle-exclamation text-lg flex-shrink-0"></i>
                <span x-text="errorMessage"></span>
            </div>

            <!-- ===== STEP 1 — EVENT DETAILS ===== -->
            <div x-show="currentStep === 1" x-transition>
                <div class="glass rounded-2xl p-8 mb-6">
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
                            <input type="text" name="name" id="event-name" value="{{ old('name') }}"
                                placeholder="e.g. Afrobeats Night Lagos" maxlength="75"
                                x-on:input="generateSlug($event.target.value)" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                            <div class="flex justify-between mt-1">
                                <p class="text-gray-600 text-xs">Max 75 characters</p>
                                <p class="text-gray-600 text-xs" id="name-count">0/75</p>
                            </div>
                        </div>

                        <!-- Event Description -->
                        <div>
                            <label class="text-gray-400 text-sm font-medium mb-2 block">
                                Event Description <span class="text-amber-400">*</span>
                            </label>
                            <textarea name="description" rows="5" placeholder="Describe your event in detail..."
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 resize-none">{{ old('description') }}</textarea>
                        </div>

                        <!-- Event Format & Category -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-gray-400 text-sm font-medium mb-2 block">
                                    Event Format <span class="text-amber-400">*</span>
                                </label>
                                <select name="event_type" required
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 appearance-none cursor-pointer">
                                    <option value="">Select format</option>
                                    @foreach (['Party', 'Performance', 'Concert', 'Festival', 'Exhibition', 'Screening', 'Market', 'Pop Up', 'Conference', 'Class', 'Workshop', 'Presentation', 'Meetup', 'Attraction', 'Tournament', 'Networking'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('event_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-gray-400 text-sm font-medium mb-2 block">
                                    Category <span class="text-amber-400">*</span>
                                </label>
                                <select name="category_id" required
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 appearance-none cursor-pointer">
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Event Mode -->
                        <div>
                            <label class="text-gray-400 text-sm font-medium mb-3 block">
                                Event Mode <span class="text-amber-400">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="event_mode" value="physical" x-model="eventMode"
                                        {{ old('event_mode', 'physical') == 'physical' ? 'checked' : '' }}
                                        class="hidden peer">
                                    <div
                                        class="glass rounded-xl p-4 border-2 border-transparent peer-checked:border-amber-400/50 peer-checked:bg-amber-400/5 transition-all duration-200 text-center">
                                        <div
                                            class="w-10 h-10 gold-gradient rounded-xl flex items-center justify-center mx-auto mb-2">
                                            <i class="fa-solid fa-location-dot text-black text-sm"></i>
                                        </div>
                                        <p class="text-white text-sm font-semibold">Physical</p>
                                        <p class="text-gray-500 text-xs mt-1">In-person venue event</p>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="event_mode" value="online" x-model="eventMode"
                                        {{ old('event_mode') == 'online' ? 'checked' : '' }} class="hidden peer">
                                    <div
                                        class="glass rounded-xl p-4 border-2 border-transparent peer-checked:border-amber-400/50 peer-checked:bg-amber-400/5 transition-all duration-200 text-center">
                                        <div
                                            class="w-10 h-10 bg-blue-500/20 border border-blue-500/30 rounded-xl flex items-center justify-center mx-auto mb-2">
                                            <i class="fa-solid fa-video text-blue-400 text-sm"></i>
                                        </div>
                                        <p class="text-white text-sm font-semibold">Online</p>
                                        <p class="text-gray-500 text-xs mt-1">Virtual event only</p>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="event_mode" value="hybrid" x-model="eventMode"
                                        {{ old('event_mode') == 'hybrid' ? 'checked' : '' }} class="hidden peer">
                                    <div
                                        class="glass rounded-xl p-4 border-2 border-transparent peer-checked:border-amber-400/50 peer-checked:bg-amber-400/5 transition-all duration-200 text-center">
                                        <div
                                            class="w-10 h-10 bg-purple-500/20 border border-purple-500/30 rounded-xl flex items-center justify-center mx-auto mb-2">
                                            <i class="fa-solid fa-layer-group text-purple-400 text-sm"></i>
                                        </div>
                                        <p class="text-white text-sm font-semibold">Hybrid</p>
                                        <p class="text-gray-500 text-xs mt-1">Physical + Online</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Location — Physical & Hybrid -->
                        <div x-show="eventMode === 'physical' || eventMode === 'hybrid'" x-transition>
                            <label class="text-gray-400 text-sm font-medium mb-2 block">
                                Venue Location <span x-show="eventMode === 'physical' || eventMode === 'hybrid'"
                                    class="text-amber-400">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <select name="country"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 appearance-none cursor-pointer">
                                    <option value="">Select country</option>
                                    @foreach (['Nigeria', 'Ghana', 'Kenya', 'South Africa', 'Rwanda', 'United Kingdom'] as $country)
                                        <option value="{{ $country }}"
                                            {{ old('country') == $country ? 'selected' : '' }}>{{ $country }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="location" value="{{ old('location') }}"
                                    placeholder="Venue name and address"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                            </div>
                        </div>

                        <!-- Online Details — Online & Hybrid -->
                        <div x-show="eventMode === 'online' || eventMode === 'hybrid'" x-transition class="space-y-4">
                            <label class="text-gray-400 text-sm font-medium block">
                                Online Event Details <span class="text-amber-400">*</span>
                            </label>

                            <!-- Platform -->
                            <div>
                                <label class="text-gray-500 text-xs mb-1 block">Platform <span
                                        class="text-amber-400">*</span></label>
                                <select name="platform"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                                    <option value="">Select platform</option>
                                    <option value="zoom" {{ old('platform') == 'zoom' ? 'selected' : '' }}>Zoom Meeting
                                    </option>
                                    <option value="zoom_webinar"
                                        {{ old('platform') == 'zoom_webinar' ? 'selected' : '' }}>Zoom Webinar</option>
                                    <option value="google_meet" {{ old('platform') == 'google_meet' ? 'selected' : '' }}>
                                        Google Meet</option>
                                    <option value="microsoft_teams"
                                        {{ old('platform') == 'microsoft_teams' ? 'selected' : '' }}>Microsoft Teams
                                    </option>
                                    <option value="youtube_live"
                                        {{ old('platform') == 'youtube_live' ? 'selected' : '' }}>YouTube Live</option>
                                    <option value="custom" {{ old('platform') == 'custom' ? 'selected' : '' }}>Custom Link
                                    </option>
                                </select>
                            </div>

                            <!-- Meeting Link -->
                            <div>
                                <label class="text-gray-500 text-xs mb-1 block">Meeting Link <span
                                        class="text-amber-400">*</span></label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-link absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                                    <input type="url" name="meeting_link" value="{{ old('meeting_link') }}"
                                        placeholder="https://zoom.us/j/123456789"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                </div>
                            </div>

                            <!-- Meeting ID & Passcode -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-gray-500 text-xs mb-1 block">Meeting ID <span
                                            class="text-gray-600">(optional)</span></label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                                        <input type="text" name="meeting_id" value="{{ old('meeting_id') }}"
                                            placeholder="123 456 789"
                                            class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-gray-500 text-xs mb-1 block">Passcode <span
                                            class="text-gray-600">(optional)</span></label>
                                    <div class="relative">
                                        <i
                                            class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                                        <input type="text" name="meeting_passcode"
                                            value="{{ old('meeting_passcode') }}" placeholder="••••••"
                                            class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                    </div>
                                </div>
                            </div>

                            <!-- WhatsApp Link -->
                            <div>
                                <label class="text-gray-500 text-xs mb-1 block">
                                    WhatsApp Group/Community Link <span class="text-amber-400">*</span>
                                </label>
                                <div class="relative">
                                    <i
                                        class="fa-brands fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-green-400 text-sm"></i>
                                    <input type="url" name="whatsapp_link" value="{{ old('whatsapp_link') }}"
                                        placeholder="https://chat.whatsapp.com/..."
                                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                </div>
                                <p class="text-gray-600 text-xs mt-1">
                                    Attendees will receive this link after successful ticket purchase.
                                </p>
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div>
                            <label class="text-gray-400 text-sm font-medium mb-2 block">
                                Date & Time <span class="text-amber-400">*</span>
                            </label>

                            <!-- Recurring Toggle -->
                            <div class="flex items-center gap-3 mb-4 p-4 glass rounded-xl cursor-pointer"
                                x-on:click="isRecurring = !isRecurring">
                                <div class="w-10 h-6 rounded-full transition-colors duration-300 relative flex-shrink-0"
                                    :class="isRecurring ? 'bg-amber-400' : 'bg-white/10'">
                                    <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-transform duration-300"
                                        :class="isRecurring ? 'translate-x-5' : 'translate-x-1'"></div>
                                </div>
                                <div>
                                    <p class="text-white text-sm font-medium">Recurring Event</p>
                                    <p class="text-gray-500 text-xs">This event repeats daily, weekly, or monthly</p>
                                </div>
                                <input type="hidden" name="is_recurring" :value="isRecurring ? '1' : '0'">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-gray-500 text-xs mb-1 block">Start Date & Time</label>
                                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                                        required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                                </div>
                                <div>
                                    <label class="text-gray-500 text-xs mb-1 block">End Date & Time</label>
                                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="text-gray-500 text-xs mb-1 block">Timezone</label>
                                <select name="timezone"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 appearance-none cursor-pointer">
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
                                            {{ old('timezone', 'Africa/Lagos') == $tz ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Recurring Options -->
                            <div x-show="isRecurring" x-transition class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-gray-500 text-xs mb-1 block">Repeat Pattern</label>
                                    <select name="recurrence_rule"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 appearance-none cursor-pointer">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-gray-500 text-xs mb-1 block">Series End Date</label>
                                    <input type="date" name="recurrence_end"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                                </div>
                            </div>
                        </div>

                        <!-- Custom URL -->
                        <div>
                            <label class="text-gray-400 text-sm font-medium mb-2 block">Event URL</label>
                            <div
                                class="flex items-center glass rounded-xl overflow-hidden border border-white/10 focus-within:border-amber-400/50 transition-all duration-200">
                                <span
                                    class="text-gray-500 text-sm px-4 py-3 border-r border-white/10 bg-white/2 whitespace-nowrap">
                                    eventplug.test/events/
                                </span>
                                <input type="text" name="slug" id="slug-input" value="{{ old('slug') }}"
                                    placeholder="your-event-slug"
                                    class="flex-1 bg-transparent px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none">
                            </div>
                            <p class="text-gray-600 text-xs mt-1">Auto-generated from event name. You can customize it.
                            </p>
                        </div>

                        <!-- Lineup -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-gray-400 text-sm font-medium">
                                    Event Lineup <span class="text-gray-600 font-normal">(optional)</span>
                                </label>
                                <button type="button" x-on:click="addLineup()"
                                    class="text-amber-400 text-xs hover:text-amber-300 transition-colors flex items-center gap-1">
                                    <i class="fa-solid fa-plus"></i> Add Member
                                </button>
                            </div>

                            <div x-show="lineup.length === 0" class="glass rounded-xl p-6 text-center">
                                <p class="text-gray-600 text-sm">No lineup added. Click "Add Member" to add performers
                                    or
                                    speakers.</p>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(member, index) in lineup" :key="index">
                                    <div class="glass rounded-xl p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-amber-400 text-xs font-medium"
                                                x-text="'Member ' + (index + 1)"></span>
                                            <button type="button" x-on:click="removeLineup(index)"
                                                class="text-gray-500 hover:text-red-400 transition-colors text-xs">
                                                <i class="fa-solid fa-times"></i> Remove
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <input type="text" :name="'lineup[' + index + '][name]'" placeholder="Name"
                                                class="bg-white/5 border border-white/10 rounded-lg px-3 py-2.5 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                            <input type="text" :name="'lineup[' + index + '][role]'"
                                                placeholder="Role / Title"
                                                class="bg-white/5 border border-white/10 rounded-lg px-3 py-2.5 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                        </div>
                                    </div>
                                </template>
                            </div>
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
                                    <input type="text" name="instagram" value="{{ old('instagram') }}"
                                        placeholder="@yourhandle"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                                </div>
                                <div class="relative">
                                    <i
                                        class="fa-brands fa-x-twitter absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="twitter" value="{{ old('twitter') }}"
                                        placeholder="@yourhandle"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                                </div>
                                <div class="relative">
                                    <i
                                        class="fa-brands fa-facebook absolute left-4 top-1/2 -translate-y-1/2 text-blue-400 text-sm"></i>
                                    <input type="text" name="facebook" value="{{ old('facebook') }}"
                                        placeholder="Facebook event link"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                                </div>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-globe absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="website" value="{{ old('website') }}"
                                        placeholder="https://yourwebsite.com"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200">
                                </div>
                            </div>
                        </div>

                        <!-- Payment Model -->
                        <div>
                            <label class="text-gray-400 text-sm font-medium mb-3 block">
                                Commission Model <span class="text-amber-400">*</span>
                                <span class="text-red-400 text-xs font-normal ml-2">⚠ Cannot be changed after
                                    publishing</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_model" value="attendee_pays"
                                        {{ old('payment_model', 'attendee_pays') == 'attendee_pays' ? 'checked' : '' }}
                                        class="hidden peer">
                                    <div
                                        class="glass rounded-xl p-4 border-2 border-transparent peer-checked:border-amber-400/50 peer-checked:bg-amber-400/5 transition-all duration-200">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 gold-gradient rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-user text-black text-xs"></i>
                                            </div>
                                            <p class="text-white text-sm font-semibold">Attendee Pays</p>
                                        </div>
                                        <p class="text-gray-500 text-xs leading-relaxed">Commission is added on top of
                                            ticket price. Guests pay slightly more.</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_model" value="manager_pays"
                                        {{ old('payment_model') == 'manager_pays' ? 'checked' : '' }} class="hidden peer">
                                    <div
                                        class="glass rounded-xl p-4 border-2 border-transparent peer-checked:border-amber-400/50 peer-checked:bg-amber-400/5 transition-all duration-200">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div
                                                class="w-8 h-8 bg-purple-500/20 border border-purple-500/30 rounded-lg flex items-center justify-center">
                                                <i class="fa-solid fa-building text-purple-400 text-xs"></i>
                                            </div>
                                            <p class="text-white text-sm font-semibold">I Pay Later</p>
                                        </div>
                                        <p class="text-gray-500 text-xs leading-relaxed">Commission deducted from your
                                            payout. Guests pay exact ticket price.</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Button -->
                <div class="flex justify-end">
                    <button type="button" x-on:click="nextStep()"
                        class="btn-gold px-8 py-3 rounded-xl text-black font-semibold text-sm flex items-center gap-2">
                        Continue to Cover Image <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ===== STEP 2 — COVER IMAGE ===== -->
            <div x-show="currentStep === 2" x-transition>
                <div class="glass rounded-2xl p-8 mb-6">
                    <h2 class="text-white font-semibold text-lg mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 gold-gradient rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-image text-black text-xs"></i>
                        </div>
                        Event Cover Image
                    </h2>

                    <div class="space-y-6">
                        <p class="text-gray-500 text-sm">Upload a stunning cover image for your event. This is the
                            first
                            thing people see.</p>

                        <!-- Upload Area -->
                        <div class="relative border-2 border-dashed border-white/10 rounded-2xl p-12 text-center hover:border-amber-400/30 transition-all duration-300 cursor-pointer"
                            id="upload-area" x-on:dragover.prevent="isDragging = true"
                            x-on:dragleave="isDragging = false" x-on:drop.prevent="handleDrop($event)"
                            :class="isDragging ? 'border-amber-400/50 bg-amber-400/5' : ''">

                            <input type="file" name="cover_image" id="cover-input" accept="image/jpeg,image/png"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                x-on:change="handleImageSelect($event)">

                            <div x-show="!imagePreview">
                                <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-cloud-arrow-up text-amber-400/50 text-2xl"></i>
                                </div>
                                <p class="text-white font-medium mb-2">Drag & drop your image here</p>
                                <p class="text-gray-500 text-sm mb-4">or click to browse files</p>
                                <p class="text-gray-600 text-xs">JPEG or PNG • Max 2MB • Recommended: 1:1 ratio</p>
                            </div>

                            <!-- Preview -->
                            <div x-show="imagePreview" class="relative">
                                <img :src="imagePreview" class="max-h-64 mx-auto rounded-xl object-cover">
                                <button type="button" x-on:click.stop="clearImage()"
                                    class="absolute top-2 right-2 w-8 h-8 bg-red-500/80 rounded-lg flex items-center justify-center text-white hover:bg-red-500 transition-colors">
                                    <i class="fa-solid fa-times text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <p class="text-gray-600 text-xs text-center">
                            You can skip this step and add the image later from your dashboard.
                        </p>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between">
                    <button type="button" x-on:click="prevStep()"
                        class="btn-outline-gold px-8 py-3 rounded-xl font-semibold text-sm flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </button>
                    <button type="button" x-on:click="nextStep()"
                        class="btn-gold px-8 py-3 rounded-xl text-black font-semibold text-sm flex items-center gap-2">
                        Continue to Tickets <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ===== STEP 3 — TICKETS ===== -->
            <div x-show="currentStep === 3" x-transition>
                <div class="glass rounded-2xl p-8 mb-6">
                    <h2 class="text-white font-semibold text-lg mb-6 flex items-center gap-3">
                        <div class="w-8 h-8 gold-gradient rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-ticket text-black text-xs"></i>
                        </div>
                        Ticket Types
                    </h2>

                    <div class="space-y-6">
                        <template x-for="(ticket, index) in tickets" :key="index">
                            <div
                                class="glass rounded-2xl p-6 border border-white/5 hover:border-amber-400/10 transition-all duration-200">

                                <!-- Ticket Header -->
                                <div class="flex items-center justify-between mb-5">
                                    <span class="text-amber-400 text-sm font-semibold"
                                        x-text="'Ticket ' + (index + 1)"></span>
                                    <button type="button" x-on:click="removeTicket(index)" x-show="tickets.length > 1"
                                        class="text-gray-500 hover:text-red-400 transition-colors text-xs flex items-center gap-1">
                                        <i class="fa-solid fa-trash"></i> Remove
                                    </button>
                                </div>

                                <div class="space-y-4">

                                    <!-- Ticket Kind & Admission -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-gray-500 text-xs mb-1 block">Ticket Kind</label>
                                            <select :name="'tickets[' + index + '][ticket_type]'" x-model="ticket.type"
                                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                                                <option value="free">Free</option>
                                                <option value="paid">Paid</option>
                                                <option value="invite_only">Invite Only</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-gray-500 text-xs mb-1 block">Admission Type</label>
                                            <select :name="'tickets[' + index + '][admission_type]'"
                                                x-model="ticket.admission"
                                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                                                <option value="single">Single (1 person)</option>
                                                <option value="group">Group (multiple people)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Ticket Name & Price -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-gray-500 text-xs mb-1 block">Ticket Name</label>
                                            <input type="text" :name="'tickets[' + index + '][name]'"
                                                placeholder="e.g. Early Bird, VIP, Regular" maxlength="75"
                                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                        </div>
                                        <div x-show="ticket.type === 'paid'">
                                            <label class="text-gray-500 text-xs mb-1 block">Price (₦)</label>
                                            <input type="number" :name="'tickets[' + index + '][price]'"
                                                placeholder="5000" min="1"
                                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                        </div>
                                    </div>

                                    <!-- Quantity & Limit -->
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="text-gray-500 text-xs mb-1 block">Total Quantity</label>
                                            <input type="number" :name="'tickets[' + index + '][quantity]'"
                                                placeholder="100" min="1"
                                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                        </div>
                                        <div>
                                            <label class="text-gray-500 text-xs mb-1 block">Purchase Limit</label>
                                            <select :name="'tickets[' + index + '][purchase_limit]'"
                                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                                                @foreach (range(1, 10) as $n)
                                                    <option value="{{ $n }}">{{ $n }} per
                                                        person
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div x-show="ticket.admission === 'group'">
                                            <label class="text-gray-500 text-xs mb-1 block">Group Size</label>
                                            <input type="number" :name="'tickets[' + index + '][group_size]'"
                                                placeholder="10" min="2"
                                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label class="text-gray-500 text-xs mb-1 block">Description (optional)</label>
                                        <input type="text" :name="'tickets[' + index + '][description]'"
                                            placeholder="e.g. Includes dinner and 2 drinks"
                                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                    </div>

                                    <!-- Perks -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-gray-500 text-xs">Perks (optional)</label>
                                            <button type="button" x-on:click="addPerk(index)"
                                                class="text-amber-400 text-xs hover:text-amber-300 transition-colors">
                                                <i class="fa-solid fa-plus"></i> Add Perk
                                            </button>
                                        </div>
                                        <div class="space-y-2">
                                            <template x-for="(perk, pIndex) in ticket.perks" :key="pIndex">
                                                <div class="flex items-center gap-2">
                                                    <input type="text"
                                                        :name="'tickets[' + index + '][perks][' + pIndex + ']'"
                                                        placeholder="e.g. Free cocktail on arrival"
                                                        class="flex-1 bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all">
                                                    <button type="button" x-on:click="removePerk(index, pIndex)"
                                                        class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-500 hover:text-red-400 transition-colors flex-shrink-0">
                                                        <i class="fa-solid fa-times text-xs"></i>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Add Ticket -->
                        <button type="button" x-on:click="addTicket()"
                            class="w-full glass rounded-2xl p-4 text-center text-amber-400 text-sm font-medium hover:border-amber-400/30 transition-all duration-200 border border-dashed border-white/10 hover:bg-amber-400/5">
                            <i class="fa-solid fa-plus mr-2"></i>Add Another Ticket Type
                        </button>
                    </div>
                </div>


                <!-- Navigation -->

                <div class="flex justify-between mb-16">
                    <button type="button" x-on:click="prevStep()"
                        class="btn-outline-gold px-8 py-3 rounded-xl font-semibold text-sm flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </button>
                    <div class="flex items-center gap-3">
                        <button type="button" x-on:click="validateAndSubmit('draft')"
                            class="btn-outline-gold px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Save as Draft
                        </button>
                        <button type="button" x-on:click="validateAndSubmit('publish')"
                            class="btn-gold px-6 py-3 rounded-xl text-black font-semibold text-sm flex items-center gap-2">
                            <i class="fa-solid fa-rocket"></i> Save & Publish
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
    <script>
        function eventWizard() {
            return {
                currentStep: 1,
                isVirtual: false,
                isRecurring: false,
                isDragging: false,
                imagePreview: null,
                errorMessage: '',
                eventMode: '{{ old('event_mode', 'physical') }}',
                lineup: [],
                tickets: [{
                    type: 'paid',
                    admission: 'single',
                    perks: []
                }],

                nextStep() {
                    if (this.currentStep === 1) {
                        const name = document.querySelector('[name="name"]')?.value.trim();
                        const description = document.querySelector('[name="description"]')?.value.trim();
                        const eventType = document.querySelector('[name="event_type"]')?.value;
                        const categoryId = document.querySelector('[name="category_id"]')?.value;
                        const startDate = document.querySelector('[name="start_date"]')?.value;
                        const endDate = document.querySelector('[name="end_date"]')?.value;
                        const paymentModel = document.querySelector('input[name="payment_model"]:checked');

                        if (!name) {
                            this.showError('Event name is required.');
                            return;
                        }
                        if (!description) {
                            this.showError('Event description is required.');
                            return;
                        }
                        if (!eventType) {
                            this.showError('Please select an event format.');
                            return;
                        }
                        if (!categoryId) {
                            this.showError('Please select a category.');
                            return;
                        }
                        if (!startDate) {
                            this.showError('Please set a start date and time.');
                            return;
                        }
                        if (!endDate) {
                            this.showError('Please set an end date and time.');
                            return;
                        }
                        if (new Date(endDate) <= new Date(startDate)) {
                            this.showError('End date must be after start date.');
                            return;
                        }
                        if (!paymentModel) {
                            this.showError('Please select a commission model.');
                            return;
                        }

                        // Validate online/hybrid fields
                        if (this.eventMode === 'online' || this.eventMode === 'hybrid') {
                            const platform = document.querySelector('[name="platform"]')?.value;
                            const meetingLink = document.querySelector('[name="meeting_link"]')?.value.trim();
                            const whatsappLink = document.querySelector('[name="whatsapp_link"]')?.value.trim();

                            if (!platform) {
                                this.showError('Please select a platform for your online event.');
                                return;
                            }
                            if (!meetingLink) {
                                this.showError('Meeting link is required for online events.');
                                return;
                            }
                            if (!whatsappLink) {
                                this.showError('WhatsApp group link is required for online events.');
                                return;
                            }
                        }

                        // Validate physical/hybrid venue
                        if (this.eventMode === 'physical' || this.eventMode === 'hybrid') {
                            const location = document.querySelector('[name="location"]')?.value.trim();
                            if (!location) {
                                this.showError('Venue location is required for physical events.');
                                return;
                            }
                        }
                    }

                    this.errorMessage = '';
                    if (this.currentStep < 3) this.currentStep++;
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                },

                prevStep() {
                    if (this.currentStep > 1) this.currentStep--;
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                },

                showError(message) {
                    this.errorMessage = message;
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                    setTimeout(() => {
                        this.errorMessage = '';
                    }, 4000);
                },

                validateAndSubmit(action) {
                    if (this.tickets.length === 0) {
                        this.showError('Please add at least one ticket type.');
                        return;
                    }

                    const ticketNameInputs = document.querySelectorAll('input[name*="[name]"]');
                    const ticketQtyInputs = document.querySelectorAll('input[name*="[quantity]"]');

                    for (let i = 0; i < this.tickets.length; i++) {
                        if (!ticketNameInputs[i]?.value.trim()) {
                            this.showError(`Ticket ${i + 1} must have a name.`);
                            return;
                        }
                        if (!ticketQtyInputs[i]?.value || parseInt(ticketQtyInputs[i].value) < 1) {
                            this.showError(`Ticket ${i + 1} must have a valid quantity.`);
                            return;
                        }
                    }

                    document.getElementById('action-input').value = action;
                    document.getElementById('event-form').submit();
                },

                generateSlug(value) {
                    const slug = value.toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .trim();
                    document.getElementById('slug-input').value = slug;
                    document.getElementById('name-count').textContent = value.length + '/75';
                },

                handleImageSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                handleDrop(event) {
                    this.isDragging = false;
                    const file = event.dataTransfer.files[0];
                    if (file && (file.type === 'image/jpeg' || file.type === 'image/png')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                clearImage() {
                    this.imagePreview = null;
                    document.getElementById('cover-input').value = '';
                },

                addLineup() {
                    this.lineup.push({
                        name: '',
                        role: ''
                    });
                },

                removeLineup(index) {
                    this.lineup.splice(index, 1);
                },

                addTicket() {
                    this.tickets.push({
                        type: 'paid',
                        admission: 'single',
                        perks: []
                    });
                },

                removeTicket(index) {
                    this.tickets.splice(index, 1);
                },

                addPerk(ticketIndex) {
                    this.tickets[ticketIndex].perks.push('');
                },

                removePerk(ticketIndex, perkIndex) {
                    this.tickets[ticketIndex].perks.splice(perkIndex, 1);
                },
            }
        }
    </script>
@endpush
