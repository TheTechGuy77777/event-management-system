@extends('layouts.admin')

@section('title', 'Categories — EventPlug')
@section('page-title', 'Event Categories')
@section('page-subtitle', 'Manage event categories shown during event creation')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Add Category Form -->
        <div class="lg:col-span-1">
            <div class="glass rounded-2xl p-6 sticky top-24">
                <h2 class="text-white font-semibold mb-5 flex items-center gap-2">
                    <div class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-plus text-black text-xs"></i>
                    </div>
                    Add New Category
                </h2>

                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">Category Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="e.g. Music, Tech, Fashion" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 @error('name') border-red-500/50 @enderror">
                        @error('name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn-gold w-full py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-plus mr-2"></i>Add Category
                    </button>
                </form>

                <!-- Stats -->
                <div class="mt-6 pt-6 border-t border-white/5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 text-sm">Total Categories</span>
                        <span class="text-white font-semibold">{{ $categories->total() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 text-sm">Active</span>
                        <span class="text-green-400 font-semibold">
                            {{ $categories->getCollection()->where('is_active', true)->count() }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 text-sm">Hidden</span>
                        <span class="text-gray-400 font-semibold">
                            {{ $categories->getCollection()->where('is_active', false)->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2">
            <div class="glass rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                    <h2 class="text-white font-semibold">All Categories</h2>
                    <span class="text-gray-500 text-sm">{{ $categories->total() }} total</span>
                </div>

                @if ($categories->count() > 0)
                    <div class="divide-y divide-white/5">
                        @foreach ($categories as $category)
                            <div
                                class="flex items-center justify-between px-6 py-4 hover:bg-white/2 transition-colors group">

                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                                {{ $category->is_active ? 'gold-gradient' : 'bg-white/5 border border-white/10' }}">
                                        <i
                                            class="fa-solid fa-tag text-xs {{ $category->is_active ? 'text-black' : 'text-gray-500' }}"></i>
                                    </div>
                                    <div>
                                        <p
                                            class="text-white text-sm font-medium group-hover:text-amber-400 transition-colors">
                                            {{ $category->name }}
                                        </p>
                                        <p class="text-gray-600 text-xs mt-0.5">
                                            Added {{ $category->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">

                                    <!-- Status Badge -->
                                    @if ($category->is_active)
                                        <span
                                            class="text-xs px-3 py-1 rounded-full text-green-400 bg-green-500/10 border border-green-500/20">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="text-xs px-3 py-1 rounded-full text-gray-500 bg-white/5 border border-white/10">
                                            Hidden
                                        </span>
                                    @endif

                                    <!-- Toggle Active -->
                                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-8 h-8 glass rounded-lg flex items-center justify-center transition-colors
                                        {{ $category->is_active
                                            ? 'text-gray-400 hover:text-yellow-400 hover:border-yellow-400/20'
                                            : 'text-gray-400 hover:text-green-400 hover:border-green-400/20' }}"
                                            title="{{ $category->is_active ? 'Hide Category' : 'Show Category' }}">
                                            <i
                                                class="fa-solid {{ $category->is_active ? 'fa-eye-slash' : 'fa-eye' }} text-xs"></i>
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                        onsubmit="return confirm('Delete category {{ $category->name }}? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 glass rounded-lg flex items-center justify-center text-gray-400 hover:text-red-400 hover:border-red-400/20 transition-colors"
                                            title="Delete Category">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($categories->hasPages())
                        <div class="px-6 py-4 border-t border-white/5">
                            {{ $categories->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-16">
                        <div class="w-16 h-16 glass rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-tags text-amber-400/30 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm">No categories yet. Add your first one!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
