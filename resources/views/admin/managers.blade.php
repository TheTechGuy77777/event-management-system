@extends('layouts.admin')

@section('title', 'Event Managers — EventPlug')
@section('page-title', 'Event Managers')
@section('page-subtitle', 'Manage all registered event managers')

@section('content')

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 gold-gradient rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-black text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">{{ $managers->total() }}</div>
                    <div class="text-gray-500 text-xs">Total Managers</div>
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
                    <div class="text-white font-bold text-xl">
                        {{ $managers->getCollection()->where('is_active', true)->where('is_banned', false)->count() }}
                    </div>
                    <div class="text-gray-500 text-xs">Active</div>
                </div>
            </div>
        </div>
        <div class="glass rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-500/20 border border-red-500/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-ban text-red-400 text-sm"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-xl">
                        {{ $managers->getCollection()->where('is_banned', true)->count() }}
                    </div>
                    <div class="text-gray-500 text-xs">Banned</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Managers Table -->
    <div class="glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Manager
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                            Organization</th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">
                            Registered</th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Events
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Status
                        </th>
                        <th class="text-left text-gray-500 text-xs font-medium uppercase tracking-wider px-6 py-4">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($managers as $manager)
                        <tr class="hover:bg-white/2 transition-colors group">

                            <!-- Manager Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl gold-gradient flex items-center justify-center flex-shrink-0">
                                        @if ($manager->profile_photo)
                                            <img src="{{ asset('storage/' . $manager->profile_photo) }}"
                                                class="w-full h-full object-cover rounded-xl">
                                        @else
                                            <span class="text-black font-bold text-sm">
                                                {{ strtoupper(substr($manager->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-medium">{{ $manager->name }}</p>
                                        <p class="text-gray-500 text-xs">{{ $manager->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Organization -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">
                                    {{ $manager->organization_name ?? '—' }}
                                </p>
                                <p class="text-gray-600 text-xs mt-0.5">{{ $manager->phone ?? '—' }}</p>
                            </td>

                            <!-- Registered -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">{{ $manager->created_at->format('d M Y') }}</p>
                                <p class="text-gray-600 text-xs mt-0.5">{{ $manager->created_at->diffForHumans() }}</p>
                            </td>

                            <!-- Events Count -->
                            <td class="px-6 py-4">
                                <p class="text-gray-300 text-sm">{{ $manager->events->count() }} events</p>
                                <p class="text-gray-600 text-xs mt-0.5">
                                    {{ $manager->events->where('status', 'published')->count() }} published
                                </p>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if ($manager->is_banned)
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full border text-red-400 bg-red-500/10 border-red-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Banned
                                    </span>
                                @elseif(!$manager->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full border text-yellow-400 bg-yellow-500/10 border-yellow-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>Suspended
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full border text-green-400 bg-green-500/10 border-green-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>Active
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">

                                    @if (!$manager->is_banned && $manager->is_active)
                                        <!-- Suspend -->
                                        <form method="POST" action="{{ route('admin.managers.suspend', $manager) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="w-8 h-8 bg-yellow-500/10 border border-yellow-500/20 rounded-lg flex items-center justify-center text-yellow-400 hover:bg-yellow-500/20 transition-colors"
                                                title="Suspend Manager"
                                                onclick="return confirm('Suspend {{ $manager->name }}?')">
                                                <i class="fa-solid fa-pause text-xs"></i>
                                            </button>
                                        </form>

                                        <!-- Ban -->
                                        <form method="POST" action="{{ route('admin.managers.ban', $manager) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="w-8 h-8 bg-red-500/10 border border-red-500/20 rounded-lg flex items-center justify-center text-red-400 hover:bg-red-500/20 transition-colors"
                                                title="Ban Manager"
                                                onclick="return confirm('Permanently ban {{ $manager->name }}? This will take all their events offline.')">
                                                <i class="fa-solid fa-ban text-xs"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if (!$manager->is_active || $manager->is_banned)
                                        <!-- Reactivate -->
                                        <form method="POST" action="{{ route('admin.managers.reactivate', $manager) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="w-8 h-8 bg-green-500/10 border border-green-500/20 rounded-lg flex items-center justify-center text-green-400 hover:bg-green-500/20 transition-colors"
                                                title="Reactivate Manager"
                                                onclick="return confirm('Reactivate {{ $manager->name }}?')">
                                                <i class="fa-solid fa-play text-xs"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- View Events -->
                                    <a href="{{ route('admin.events') }}?manager={{ $manager->id }}"
                                        class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-400 transition-colors"
                                        title="View Events">
                                        <i class="fa-solid fa-calendar text-xs"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-users text-amber-400/30 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">No event managers registered yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($managers->hasPages())
            <div class="px-6 py-4 border-t border-white/5">
                {{ $managers->links() }}
            </div>
        @endif
    </div>

@endsection
