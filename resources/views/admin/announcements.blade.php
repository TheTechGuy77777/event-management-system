@extends('layouts.admin')

@section('title', 'Announcements — EventPlug')
@section('page-title', 'Announcements')
@section('page-subtitle', 'Send messages to event managers')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Send Announcement -->
        <div class="lg:col-span-1">
            <div class="glass rounded-2xl p-6 sticky top-24">
                <h2 class="text-white font-semibold mb-2 flex items-center gap-2">
                    <div class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-bullhorn text-black text-xs"></i>
                    </div>
                    New Announcement
                </h2>
                <p class="text-gray-500 text-xs mb-5 leading-relaxed">
                    Send a message to all or a specific event manager.
                </p>

                <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
                    @csrf

                    <!-- Recipient -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Send To <span class="text-amber-400">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-users absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <select name="recipient_id"
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all appearance-none cursor-pointer">
                                <option value="">All Event Managers ({{ $totalManagers }})</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}">
                                        {{ $manager->name }} — {{ $manager->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Subject <span class="text-amber-400">*</span>
                        </label>
                        <input type="text" name="subject" value="{{ old('subject') }}" placeholder="e.g. Platform Update"
                            required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all @error('subject') border-red-500/50 @enderror">
                        @error('subject')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Message <span class="text-amber-400">*</span>
                        </label>
                        <textarea name="message" rows="5" placeholder="Write your announcement here..." required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all resize-none @error('message') border-red-500/50 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info -->
                    <div class="glass-gold rounded-xl p-3">
                        <p class="text-amber-400 text-xs">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            Emails are sent via background queue. Recipients also get an in-app notification.
                        </p>
                    </div>

                    <button type="submit" class="btn-gold w-full py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Send Announcement
                    </button>
                </form>
            </div>
        </div>

        <!-- Announcement History -->
        <div class="lg:col-span-2">
            <div class="glass rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                    <h2 class="text-white font-semibold">Announcement History</h2>
                    <span class="text-gray-500 text-sm">{{ $announcements->total() }} sent</span>
                </div>

                @if ($announcements->count() > 0)
                    <div class="divide-y divide-white/5">
                        @foreach ($announcements as $announcement)
                            <div class="px-6 py-5 hover:bg-white/2 transition-colors">
                                <div class="flex items-start gap-4">

                                    <!-- Icon -->
                                    <div
                                        class="w-10 h-10 gold-gradient rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fa-solid fa-bullhorn text-black text-xs"></i>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3 mb-1">
                                            <p class="text-white text-sm font-semibold">{{ $announcement->subject }}</p>
                                            <span class="text-gray-600 text-xs whitespace-nowrap flex-shrink-0">
                                                {{ $announcement->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-gray-500 text-xs leading-relaxed mb-2 line-clamp-2">
                                            {{ $announcement->message }}
                                        </p>
                                        <div class="flex items-center gap-3">
                                            <!-- Recipient Badge -->
                                            @if ($announcement->recipient_type === 'all')
                                                <span class="glass px-2 py-0.5 rounded-full text-amber-400 text-xs">
                                                    <i class="fa-solid fa-users mr-1"></i>
                                                    All Managers ({{ $announcement->recipients_count }})
                                                </span>
                                            @else
                                                <span class="glass px-2 py-0.5 rounded-full text-blue-400 text-xs">
                                                    <i class="fa-solid fa-user mr-1"></i>
                                                    {{ $announcement->recipient_name }}
                                                </span>
                                            @endif

                                            <!-- Sent Badge -->
                                            <span class="glass-gold px-2 py-0.5 rounded-full text-amber-400 text-xs">
                                                <i class="fa-solid fa-circle-check mr-1"></i>Sent
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($announcements->hasPages())
                        <div class="px-6 py-4 border-t border-white/5">
                            {{ $announcements->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-16">
                        <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-bullhorn text-amber-400/30 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm mb-2">No announcements sent yet.</p>
                        <p class="text-gray-600 text-xs">Use the form to send your first announcement.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
