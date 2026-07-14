@extends('layouts.public')

@section('title', '500 — Server Error')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-600/3 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center">
            <div class="font-display text-9xl font-bold text-red-400 mb-4">500</div>
            <h1 class="text-white text-3xl font-bold mb-4">Server Error</h1>
            <p class="text-gray-400 text-lg mb-8 max-w-md mx-auto">
                Something went wrong on our end. We're working to fix it. Please try again shortly.
            </p>
            <a href="{{ route('home') }}" class="btn-gold px-8 py-3 rounded-xl text-black font-semibold text-sm inline-block">
                <i class="fa-solid fa-home mr-2"></i>Go Home
            </a>
        </div>
    </div>
@endsection
