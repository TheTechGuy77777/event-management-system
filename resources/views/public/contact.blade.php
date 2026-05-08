@extends('layouts.public')

@section('title', 'Contact — EventPlug')

@section('content')

    <div class="max-w-3xl mx-auto px-6 py-16">

        <h1 class="text-4xl font-bold mb-4">Contact Us</h1>

        <p class="text-gray-600 mb-8">
            Have questions? Fill out the form below.
        </p>

        <form class="space-y-6">

            <div>
                <label class="block mb-2 font-medium">Full Name</label>

                <input type="text"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>

            <div>
                <label class="block mb-2 font-medium">Email Address</label>

                <input type="email"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>

            <div>
                <label class="block mb-2 font-medium">Message</label>

                <textarea rows="6"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-400 focus:outline-none"></textarea>
            </div>

            <button type="submit"
                class="bg-amber-400 hover:bg-amber-500 text-black font-semibold px-6 py-3 rounded-lg transition duration-200">
                Send Message
            </button>

        </form>

    </div>

@endsection
