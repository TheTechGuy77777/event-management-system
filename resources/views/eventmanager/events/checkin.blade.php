@extends('layouts.dashboard')

@section('title', 'Check-In — ' . $event->name)
@section('page-title', 'Check-In Screen')
@section('page-subtitle', $event->name)

@section('content')

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('dashboard.events.index') }}"
            class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors text-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to My Events
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 gold-gradient rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-black text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">{{ $totalAttendees }}</div>
                    <div class="text-gray-500 text-xs">Total Expected</div>
                </div>
            </div>
        </div>
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-green-500/20 border border-green-500/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-green-400 text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">{{ $totalCheckedIn }}</div>
                    <div class="text-gray-500 text-xs">Checked In</div>
                </div>
            </div>
        </div>
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-500/20 border border-blue-500/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-clock text-blue-400 text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">
                        {{ $totalAttendees - $totalCheckedIn }}
                    </div>
                    <div class="text-gray-500 text-xs">Remaining</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    @if ($totalAttendees > 0)
        <div class="glass rounded-2xl p-5 mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-white text-sm font-medium">Check-in Progress</span>
                <span class="text-amber-400 text-sm font-bold">
                    {{ round(($totalCheckedIn / $totalAttendees) * 100) }}%
                </span>
            </div>
            <div class="w-full bg-white/5 rounded-full h-3">
                <div class="gold-gradient h-3 rounded-full transition-all duration-500"
                    style="width: {{ ($totalCheckedIn / $totalAttendees) * 100 }}%"></div>
            </div>
            <p class="text-gray-500 text-xs mt-2">
                {{ $totalCheckedIn }} of {{ $totalAttendees }} attendees checked in
            </p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Check-In Form -->
        <div class="space-y-6">

            <!-- Manual Entry -->
            <div class="glass rounded-2xl p-6">
                <h2 class="text-white font-semibold mb-2 flex items-center gap-2">
                    <div class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-keyboard text-black text-xs"></i>
                    </div>
                    Manual Code Entry
                </h2>
                <p class="text-gray-500 text-xs mb-5">
                    Type or paste the attendee's ticket code to check them in.
                </p>

                <div class="space-y-4">
                    <div class="relative">
                        <i class="fa-solid fa-ticket absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="text" id="ticket-code-input" placeholder="e.g. EVT-A3K9XZ" maxlength="10"
                            oninput="this.value = this.value.toUpperCase()"
                            class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 font-mono tracking-wider">
                    </div>
                    <button onclick="checkIn()" id="checkin-btn"
                        class="btn-gold w-full py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-qrcode mr-2"></i>Check In Attendee
                    </button>
                </div>
            </div>

            <!-- Result Card -->
            <div id="result-card" class="hidden">
                <div id="result-content" class="glass rounded-2xl p-6 text-center">
                </div>
            </div>
        </div>

        <!-- Recent Check-Ins -->
        <div class="glass rounded-2xl p-6">
            <h2 class="text-white font-semibold mb-5 flex items-center gap-2">
                <div class="w-7 h-7 bg-green-500/20 border border-green-500/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-clock-rotate-left text-green-400 text-xs"></i>
                </div>
                Recent Check-Ins
            </h2>

            @if ($recentCheckIns->count() > 0)
                <div class="space-y-3" id="recent-list">
                    @foreach ($recentCheckIns as $checkIn)
                        <div class="flex items-center gap-3 p-3 bg-white/2 rounded-xl">
                            <div
                                class="w-9 h-9 rounded-xl bg-green-500/20 border border-green-500/30 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-check text-green-400 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-sm font-medium truncate">
                                    {{ $checkIn->attendee_name }}
                                </p>
                                <p class="text-gray-500 text-xs mt-0.5">
                                    {{ $checkIn->ticket->name ?? '—' }} •
                                    {{ $checkIn->checked_in_at?->format('h:i A') }}
                                </p>
                            </div>
                            <span class="text-green-400 text-xs font-mono flex-shrink-0">
                                {{ $checkIn->ticket_code }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8" id="no-checkins">
                    <div class="w-14 h-14 glass rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-qrcode text-amber-400/30 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm">No check-ins yet.</p>
                    <p class="text-gray-600 text-xs mt-1">Check-ins will appear here in real time.</p>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const CHECKIN_URL = '{{ route('dashboard.events.checkin.scan', $event) }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        async function checkIn() {
            const input = document.getElementById('ticket-code-input');
            const code = input.value.trim();

            if (!code) {
                showResult('error', 'Please enter a ticket code.');
                return;
            }

            const btn = document.getElementById('checkin-btn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Checking...';
            btn.disabled = true;

            try {
                const response = await fetch(CHECKIN_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: JSON.stringify({
                        ticket_code: code
                    }),
                });

                const data = await response.json();

                if (data.status === 'success') {
                    showResult('success', data);
                    addToRecentList(data);
                    input.value = '';
                } else if (data.status === 'already_used') {
                    showResult('already_used', data);
                } else {
                    showResult('invalid', data);
                }

            } catch (error) {
                showResult('error', {
                    message: 'Something went wrong. Please try again.'
                });
            }

            btn.innerHTML = '<i class="fa-solid fa-qrcode mr-2"></i>Check In Attendee';
            btn.disabled = false;
            input.focus();
        }

        function showResult(status, data) {
            const card = document.getElementById('result-card');
            const content = document.getElementById('result-content');

            card.classList.remove('hidden');

            if (status === 'success') {
                content.className = 'bg-green-500/10 border border-green-500/20 rounded-2xl p-6 text-center';
                content.innerHTML = `
                <div class="w-16 h-16 bg-green-500/20 border border-green-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-circle-check text-green-400 text-3xl"></i>
                </div>
                <h3 class="text-green-400 font-bold text-xl mb-1">Check-In Successful!</h3>
                <p class="text-white font-semibold text-lg">${data.attendee_name}</p>
                <p class="text-gray-400 text-sm mt-1">${data.ticket_type}</p>
                <p class="text-gray-600 text-xs mt-2 font-mono">${data.ticket_code}</p>
            `;
            } else if (status === 'already_used') {
                content.className = 'bg-yellow-500/10 border border-yellow-500/20 rounded-2xl p-6 text-center';
                content.innerHTML = `
                <div class="w-16 h-16 bg-yellow-500/20 border border-yellow-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-yellow-400 text-3xl"></i>
                </div>
                <h3 class="text-yellow-400 font-bold text-xl mb-1">Already Checked In</h3>
                <p class="text-white font-semibold">${data.attendee_name}</p>
                <p class="text-gray-400 text-sm mt-1">${data.ticket_type}</p>
                <p class="text-gray-500 text-xs mt-2">Checked in at ${data.checked_in_at}</p>
            `;
            } else {
                content.className = 'bg-red-500/10 border border-red-500/20 rounded-2xl p-6 text-center';
                content.innerHTML = `
                <div class="w-16 h-16 bg-red-500/20 border border-red-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-circle-xmark text-red-400 text-3xl"></i>
                </div>
                <h3 class="text-red-400 font-bold text-xl mb-1">Invalid Ticket</h3>
                <p class="text-gray-400 text-sm mt-1">${data.message}</p>
            `;
            }

            // Auto hide after 5 seconds
            setTimeout(() => {
                card.classList.add('hidden');
            }, 5000);
        }

        function addToRecentList(data) {
            const list = document.getElementById('recent-list');
            const noCheckins = document.getElementById('no-checkins');

            if (noCheckins) noCheckins.remove();

            if (!list) {
                const container = document.querySelector('.space-y-3');
                return;
            }

            const now = new Date();
            const time = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });

            const item = document.createElement('div');
            item.className = 'flex items-center gap-3 p-3 bg-green-500/5 border border-green-500/10 rounded-xl';
            item.innerHTML = `
            <div class="w-9 h-9 rounded-xl bg-green-500/20 border border-green-500/30 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-check text-green-400 text-xs"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-sm font-medium truncate">${data.attendee_name}</p>
                <p class="text-gray-500 text-xs mt-0.5">${data.ticket_type} • ${time}</p>
            </div>
            <span class="text-green-400 text-xs font-mono flex-shrink-0">${data.ticket_code}</span>
        `;

            list.insertBefore(item, list.firstChild);
        }

        // Allow Enter key to trigger check-in
        document.getElementById('ticket-code-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') checkIn();
        });
    </script>
@endpush
