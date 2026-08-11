@extends('layouts.public')

@section('title', 'Refund Policy')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <div class="mb-10">
            <h1 class="text-white text-3xl lg:text-4xl font-bold mb-3">Refund Policy</h1>
            <p class="text-gray-500 text-sm">Last updated: {{ date('F j, Y') }}</p>
        </div>

        <div class="glass rounded-3xl p-8 lg:p-10">
            <div class="prose prose-invert max-w-none text-gray-400 text-sm leading-relaxed space-y-6">

                <p>
                    This Refund Policy explains when and how ticket purchases made through {{ config('app.name') }}
                    may be refunded. This policy forms part of, and should be read alongside, our
                    <a href="{{ route('terms') }}" class="text-amber-400 hover:underline">Terms & Conditions</a>.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">1. General Policy — Tickets Are Non-Refundable</h2>
                <p>
                    All ticket purchases made through {{ config('app.name') }} are final and non-refundable, except
                    in the specific circumstances described below. By completing a purchase, you acknowledge and
                    agree that you are not entitled to a refund simply because you are unable to attend, change your
                    mind, or enter incorrect information during checkout.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">2. When a Refund Applies</h2>
                <p>You are entitled to a full refund of your ticket price in the following circumstance:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong class="text-white">The event is cancelled by the Organizer.</strong> If an Organizer cancels
                        an event after tickets have been sold, all Attendees who purchased a valid ticket for that event
                        will be refunded in full.</li>
                </ul>
                <p>
                    Refunds in this case are processed back to the original payment method used at checkout.
                    Please allow up to 5–10 business days for the refund to reflect, depending on your bank or
                    payment provider's processing times.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">3. Event Postponement</h2>
                <p>
                    If an Organizer postpones an event to a new date rather than cancelling it, your existing ticket
                    will remain valid for the new date. Refunds are not automatically issued for postponed events;
                    however, you may contact the Organizer directly to discuss your options.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">4. Circumstances That Do Not Qualify for a Refund
                </h2>
                <p>Refunds will not be issued for reasons including, but not limited to:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Change of mind or personal scheduling conflicts.</li>
                    <li>Failure to attend the event for any reason.</li>
                    <li>Incorrect information entered by the buyer at checkout (e.g. wrong email address or attendee name).
                    </li>
                    <li>Dissatisfaction with the event itself, its content, lineup changes, or venue conditions, where the
                        event still took place as scheduled.</li>
                    <li>Purchase of duplicate tickets by mistake, where used or verifiably valid.</li>
                </ul>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">5. How Refunds Are Processed</h2>
                <p>
                    Where a refund applies under this policy, {{ config('app.name') }} will initiate the refund via
                    our payment processor (Paystack or Monnify) to the original payment method used. We do not issue
                    cash refunds or refunds to a different account or card than the one used for the original
                    purchase.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">6. Platform Commission on Refunds</h2>
                <p>
                    Where a full refund is issued due to event cancellation, {{ config('app.name') }} will refund
                    the full amount paid by the Attendee, including any service fee collected at checkout under the
                    attendee-pays commission model.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">7. How to Request a Refund</h2>
                <p>
                    If you believe you are entitled to a refund under this policy, please reach out via our
                    <a href="{{ route('contact') }}" class="text-amber-400 hover:underline">Contact page</a> with
                    your order reference number and the name of the event. We aim to review and respond to refund
                    requests within 3–5 business days.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">8. Changes to This Policy</h2>
                <p>
                    We may update this Refund Policy from time to time. Any changes will apply to purchases made
                    after the updated policy takes effect and will be reflected by the "Last updated" date at the
                    top of this page.
                </p>

            </div>
        </div>
    </div>

@endsection
