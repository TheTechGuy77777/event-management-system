@extends('layouts.dashboard')

@section('title', 'My Events — EventPlug')
@section('page-title', 'My Events')
@section('page-subtitle', 'Manage all your events')

@section('content')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-gray-500 text-sm">{{ $events->total() }} event(s) found</p>
        </div>
        <a href="{{ route('dashboard.events.create') }}"
            class="btn-gold px-5 py-2.5 rounded-xl text-black font-semibold text-sm inline-flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>Create New Event
        </a>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2">
        @foreach (['all' => 'All', 'published' => 'Published', 'draft' => 'Draft', 'ended' => 'Ended', 'cancelled' => 'Cancelled'] as $value => $label)
            <a href="{{ route('dashboard.events.index', ['status' => $value]) }}"
                class="px-4 py-2 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 flex-shrink-0
           {{ request('status', 'all') === $value
               ? 'glass-gold text-amber-400 border border-amber-400/20'
               : 'glass text-gray-400 hover:text-white' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Events Table -->
    @if ($events->count() > 0)
        <div class="glass rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Event
                            </th>
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Date
                            </th>
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                                Status</th>
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                                Tickets Sold</th>
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                                Revenue</th>
                            <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach ($events as $event)
                            <tr class="hover:bg-white/2 transition-colors group">

                                <!-- Event Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-white/5 flex-shrink-0">
                                            @if ($event->cover_image)
                                                <img src="{{ asset('storage/' . $event->cover_image) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i class="fa-solid fa-calendar text-amber-400/30"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-white text-sm font-medium truncate max-w-[200px] group-hover:text-amber-400 transition-colors">
                                                {{ $event->name }}
                                            </p>
                                            <p class="text-gray-500 text-xs mt-0.5">
                                                {{ $event->is_virtual ? 'Virtual' : $event->location ?? 'No location' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4">
                                    <p class="text-gray-300 text-sm">
                                        {{ $event->start_date?->format('d M Y') ?? 'Not set' }}
                                    </p>
                                    <p class="text-gray-500 text-xs mt-0.5">
                                        {{ $event->start_date?->format('h:i A') ?? '' }}
                                    </p>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    @php
                                        $statusConfig = [
                                            'published' => [
                                                'color' => 'text-green-400 bg-green-500/10 border-green-500/20',
                                                'dot' => 'bg-green-400',
                                            ],
                                            'draft' => [
                                                'color' => 'text-gray-400 bg-white/5 border-white/10',
                                                'dot' => 'bg-gray-400',
                                            ],
                                            'ended' => [
                                                'color' => 'text-blue-400 bg-blue-500/10 border-blue-500/20',
                                                'dot' => 'bg-blue-400',
                                            ],
                                            'cancelled' => [
                                                'color' => 'text-red-400 bg-red-500/10 border-red-500/20',
                                                'dot' => 'bg-red-400',
                                            ],
                                        ];
                                        $config = $statusConfig[$event->status] ?? $statusConfig['draft'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full border {{ $config['color'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>

                                <!-- Tickets Sold -->
                                <td class="px-6 py-4">
                                    <p class="text-gray-300 text-sm">
                                        {{ $event->tickets->sum('quantity_sold') }}
                                        <span class="text-gray-600">/ {{ $event->tickets->sum('quantity') }}</span>
                                    </p>
                                </td>

                                <!-- Revenue -->
                                <td class="px-6 py-4">
                                    <p class="text-amber-400 text-sm font-medium">
                                        ₦{{ number_format($event->orders->where('payment_status', 'completed')->sum('manager_earnings')) }}
                                    </p>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">

                                        <!-- View Public Page -->
                                        <a href="/events/{{ $event->slug }}" target="_blank"
                                            class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-400 transition-colors"
                                            title="View Public Page">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>

                                        <!-- Download Event QR Code -->
                                        @if ($event->status === 'published' && $event->qr_code)
                                            <a href="{{ asset('storage/' . $event->qr_code) }}"
                                                download="eventplug-qr-{{ $event->slug }}.png"
                                                class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-400 transition-colors"
                                                title="Download QR Code">
                                                <i class="fa-solid fa-qrcode text-xs"></i>
                                            </a>
                                        @endif

                                        <!-- Publish Button (draft only) -->
                                        @if ($event->status === 'draft')
                                            <form method="POST" action="{{ route('dashboard.events.publish', $event) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="w-8 h-8 bg-green-500/10 border border-green-500/20 rounded-lg flex items-center justify-center text-green-400 hover:bg-green-500/20 transition-colors"
                                                    title="Publish Event">
                                                    <i class="fa-solid fa-rocket text-xs"></i>
                                                </button>
                                            </form>

                                            <!-- Edit Button (draft only) -->
                                            <a href="{{ route('dashboard.events.edit', $event) }}"
                                                class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-400 transition-colors"
                                                title="Edit Event">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                        @endif

                                        <!-- Attendees -->
                                        <a href="{{ route('dashboard.events.attendees', $event) }}"
                                            class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-400 transition-colors"
                                            title="View Attendees">
                                            <i class="fa-solid fa-users text-xs"></i>
                                        </a>

                                        <!-- Waitlist -->
                                        <a href="{{ route('dashboard.events.waitlist', $event) }}"
                                            class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-400 transition-colors"
                                            title="View Waitlist">
                                            <i class="fa-solid fa-bell text-xs"></i>
                                        </a>

                                        <!-- Check In -->
                                        <a href="{{ route('dashboard.events.checkin', $event) }}"
                                            class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-purple-400 transition-colors"
                                            title="Check In">
                                            <i class="fa-solid fa-qrcode text-xs"></i>
                                        </a>

                                        <!-- Delete -->
                                        @if ($event->status !== 'cancelled')
                                            @php $hasSales = $event->orders->where('payment_status', 'completed')->count() > 0; @endphp
                                            <button
                                                onclick="confirmDelete('{{ $event->name }}', '{{ $event->id }}', {{ $hasSales ? 'true' : 'false' }})"
                                                class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-red-400 transition-colors"
                                                title="{{ $hasSales ? 'Cancel Event' : 'Delete Event' }}">
                                                <i class="fa-solid {{ $hasSales ? 'fa-ban' : 'fa-trash' }} text-xs"></i>
                                            </button>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="glass rounded-2xl p-16 text-center">
            <div class="w-20 h-20 glass rounded-3xl flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-calendar-plus text-amber-400/30 text-3xl"></i>
            </div>
            <h3 class="text-white font-semibold text-xl mb-2">No events found</h3>
            <p class="text-gray-500 text-sm mb-8">
                @if (request('status') && request('status') !== 'all')
                    No {{ request('status') }} events yet.
                @else
                    You haven't created any events yet. Let's change that!
                @endif
            </p>
            <a href="{{ route('dashboard.events.create') }}"
                class="btn-gold px-6 py-3 rounded-xl text-black font-semibold text-sm inline-block">
                Create Your First Event
            </a>
        </div>
    @endif

    <!-- Delete/Cancel Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="glass rounded-3xl p-8 w-full max-w-md relative z-10 border border-red-500/20">
            <div
                class="w-14 h-14 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-400 text-xl"></i>
            </div>
            <h3 class="text-white font-bold text-xl text-center mb-2" id="delete-modal-title">Delete Event?</h3>
            <p class="text-gray-500 text-sm text-center mb-4" id="delete-modal-description">
                You are about to delete <span class="text-white font-medium" id="delete-event-name"></span>.
                This action cannot be undone.
            </p>
            <div class="mb-4">
                <label class="text-gray-400 text-sm mb-2 block">Type the event name to confirm:</label>
                <input type="text" id="confirm-name-input" placeholder="Type event name here..."
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-red-500/50 transition-all">
            </div>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 btn-outline-gold py-3 rounded-xl font-semibold text-sm">
                    Go Back
                </button>
                <button onclick="submitDelete()" id="delete-modal-btn"
                    class="flex-1 bg-red-500/20 border border-red-500/30 text-red-400 py-3 rounded-xl font-semibold text-sm hover:bg-red-500/30 transition-colors">
                    Delete Event
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let currentEventName = '';
        let currentEventId = '';

        let hasSales = false;

        function confirmDelete(name, id, eventHasSales) {
            currentEventName = name;
            currentEventId = id;
            hasSales = eventHasSales;

            document.getElementById('delete-event-name').textContent = name;
            document.getElementById('confirm-name-input').value = '';

            // Update modal text based on whether tickets were sold
            if (hasSales) {
                document.getElementById('delete-modal-title').textContent = 'Cancel Event?';
                document.getElementById('delete-modal-description').textContent =
                    'This event has ticket holders. Cancelling will notify all attendees by email. This action cannot be undone.';
                document.getElementById('delete-modal-btn').textContent = 'Cancel Event';
                document.getElementById('delete-modal-btn').className =
                    'flex-1 bg-red-500/20 border border-red-500/30 text-red-400 py-3 rounded-xl font-semibold text-sm hover:bg-red-500/30 transition-colors';
            } else {
                document.getElementById('delete-modal-title').textContent = 'Delete Event?';
                document.getElementById('delete-modal-description').textContent =
                    'This event has no ticket holders. It will be permanently deleted. This action cannot be undone.';
                document.getElementById('delete-modal-btn').textContent = 'Delete Event';
                document.getElementById('delete-modal-btn').className =
                    'flex-1 bg-red-500/20 border border-red-500/30 text-red-400 py-3 rounded-xl font-semibold text-sm hover:bg-red-500/30 transition-colors';
            }

            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function submitDelete() {
            const input = document.getElementById('confirm-name-input').value;
            if (input !== currentEventName) {
                alert('Event name does not match. Please type the exact event name.');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/dashboard/events/' + currentEventId;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
