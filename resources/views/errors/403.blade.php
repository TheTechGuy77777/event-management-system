@extends('layouts.public')

@section('title', '403 — Unauthorized')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center">
            <div class="font-display text-9xl font-bold gold-text mb-4">403</div>
            <h1 class="text-white text-3xl font-bold mb-4">Access Denied</h1>
            <p class="text-gray-400 text-lg mb-8 max-w-md mx-auto">
                You don't have permission to access this page.
            </p>
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('home') }}" class="btn-gold px-8 py-3 rounded-xl text-black font-semibold text-sm">
                    <i class="fa-solid fa-home mr-2"></i>Go Home
                </a>
                <a href="{{ route('login') }}" class="btn-outline-gold px-8 py-3 rounded-xl font-semibold text-sm">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i>Sign In
                </a>
            </div>
        </div>
    </div>
@endsection
