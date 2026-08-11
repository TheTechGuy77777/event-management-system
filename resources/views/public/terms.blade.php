@extends('layouts.public')

@section('title', 'Terms & Conditions')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <div class="mb-10">
            <h1 class="text-white text-3xl lg:text-4xl font-bold mb-3">Terms & Conditions</h1>
            <p class="text-gray-500 text-sm">Last updated: {{ date('F j, Y') }}</p>
        </div>

        <div class="glass rounded-3xl p-8 lg:p-10">
            <div class="prose prose-invert max-w-none text-gray-400 text-sm leading-relaxed space-y-6">

                <p>
                    These Terms & Conditions ("Terms") govern your access to and use of {{ config('app.name') }}
                    ("{{ config('app.name') }}", "we", "us", or "our"), a platform that enables event organizers
                    to create, publish, and sell tickets to events, and enables attendees to discover and purchase
                    tickets to those events. By accessing or using {{ config('app.name') }}, you agree to be bound
                    by these Terms. If you do not agree, please do not use the platform.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">1. Who We Are</h2>
                <p>
                    {{ config('app.name') }} is an online marketplace that connects event organizers ("Organizers")
                    with people who wish to attend their events ("Attendees"). We provide the technology to create
                    events, sell tickets, process payments, and manage attendee check-in. We are an intermediary
                    platform — we do not organize, host, or run the events listed on {{ config('app.name') }}.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">2. Eligibility</h2>
                <p>
                    You must be at least 18 years old, or the age of legal majority in your jurisdiction, to create
                    an account or make a purchase on {{ config('app.name') }}. If you are under this age, you may
                    only use the platform with the involvement and consent of a parent or legal guardian.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">3. Account Registration</h2>
                <p>
                    To create or manage events as an Organizer, you must register for an account and verify your
                    email address via a one-time verification code. You are responsible for maintaining the
                    confidentiality of your account credentials and for all activity that occurs under your account.
                    You agree to provide accurate, current, and complete information during registration and to keep
                    this information up to date.
                </p>
                <p>
                    We reserve the right to suspend or permanently disable any account that we reasonably believe
                    has provided false information, engaged in fraudulent activity, or violated these Terms.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">4. Organizer Obligations</h2>
                <p>As an Organizer using {{ config('app.name') }} to create and sell tickets to an event, you agree that
                    you:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Will provide accurate, complete, and non-misleading information about your event, including date,
                        time, location, pricing, and ticket types.</li>
                    <li>Are solely responsible for obtaining any permits, licenses, insurance, or approvals required by law
                        to host your event.</li>
                    <li>Will honor every valid ticket sold through {{ config('app.name') }} and provide entry to attendees
                        presenting a valid ticket code or QR code.</li>
                    <li>Are solely responsible for the safety, conduct, and legality of your event and all activities that
                        occur at it.</li>
                    <li>Will not use {{ config('app.name') }} to sell tickets to fraudulent, illegal, or non-existent
                        events.</li>
                    <li>Understand that {{ config('app.name') }} deducts a platform commission from ticket sales as
                        described in our published pricing, and that this commission structure may be applied either to the
                        attendee's payment or deducted from your earnings, depending on the commission model you select at
                        event creation.</li>
                </ul>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">5. Attendee Obligations</h2>
                <p>As an Attendee purchasing a ticket through {{ config('app.name') }}, you agree that you:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Will provide accurate personal information (name, email, phone number) at checkout, as this
                        information is used to generate your ticket and may be required for entry.</li>
                    <li>Understand that your ticket code and/or QR code is unique to you and must be presented at the event
                        entrance for admission.</li>
                    <li>Are responsible for reviewing event details (date, time, location, age restrictions, and any other
                        conditions) before completing a purchase.</li>
                    <li>Understand that {{ config('app.name') }} is not the organizer of the event and is not responsible
                        for the conduct of the event itself, including but not limited to event quality, cancellation by the
                        Organizer, changes to advertised lineups, or venue conditions.</li>
                </ul>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">6. Payments</h2>
                <p>
                    All payments made through {{ config('app.name') }} are processed by third-party, PCI-compliant
                    payment processors (currently Paystack and, where available, Monnify). {{ config('app.name') }}
                    does not collect or store your full card details at any point — this information is handled
                    directly by our payment processors in accordance with their own security standards.
                </p>
                <p>
                    Ticket prices are displayed in Nigerian Naira (₦) unless otherwise stated. Depending on the
                    commission model chosen by the Organizer, a service fee may be added to the ticket price at
                    checkout, or deducted from the Organizer's payout — this will be made clear to you before
                    payment is completed.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">7. Cancellations, Postponements & Refunds</h2>
                <p>
                    Our refund policy is governed by our
                    <a href="{{ route('refund-policy') }}" class="text-amber-400 hover:underline">Refund Policy</a>,
                    which forms part of these Terms. In general, ticket purchases are non-refundable except where
                    an event is cancelled by the Organizer.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">8. Prohibited Conduct</h2>
                <p>You agree not to use {{ config('app.name') }} to:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Create or promote fraudulent, misleading, illegal, or non-existent events.</li>
                    <li>Resell tickets at inflated prices in violation of applicable law or attempt to circumvent our
                        ticketing system.</li>
                    <li>Upload content that is defamatory, obscene, hateful, or infringes on the intellectual property or
                        rights of others.</li>
                    <li>Attempt to interfere with, disrupt, or gain unauthorized access to the platform, its servers, or
                        other users' accounts.</li>
                    <li>Use automated means (bots, scrapers) to access or interact with the platform without our express
                        permission.</li>
                </ul>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">9. Intellectual Property</h2>
                <p>
                    The {{ config('app.name') }} name, logo, website, and underlying software are the property of
                    {{ config('app.name') }} and are protected by applicable intellectual property laws. Organizers
                    retain ownership of the content they upload (event descriptions, images, lineup information) but
                    grant {{ config('app.name') }} a non-exclusive license to display and distribute this content for
                    the purpose of operating the platform.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">10. Limitation of Liability</h2>
                <p>
                    {{ config('app.name') }} acts solely as an intermediary platform connecting Organizers and
                    Attendees. To the fullest extent permitted by law, {{ config('app.name') }} shall not be liable
                    for any direct, indirect, incidental, or consequential damages arising from: (a) an Organizer's
                    failure to hold, deliver, or properly conduct an event; (b) any dispute between an Organizer and
                    an Attendee; (c) inaccurate event information provided by an Organizer; or (d) any loss, injury,
                    or damage occurring at an event. Our total liability to you for any claim arising from your use
                    of the platform shall not exceed the total fees paid by you to {{ config('app.name') }} in the
                    twelve (12) months preceding the claim.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">11. Account Suspension & Termination</h2>
                <p>
                    We reserve the right to suspend, restrict, or permanently terminate any account that violates
                    these Terms, engages in fraudulent activity, or poses a risk to other users or the platform, at
                    our sole discretion and without prior notice where circumstances warrant.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">12. Changes to These Terms</h2>
                <p>
                    We may update these Terms from time to time. Material changes will be communicated via email or
                    a notice on the platform. Continued use of {{ config('app.name') }} after changes take effect
                    constitutes acceptance of the revised Terms.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">13. Governing Law</h2>
                <p>
                    These Terms are governed by the laws of the Federal Republic of Nigeria. Any disputes arising
                    from these Terms or your use of {{ config('app.name') }} shall be subject to the exclusive
                    jurisdiction of the courts of Nigeria.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">14. Contact Us</h2>
                <p>
                    If you have questions about these Terms, please reach out via our
                    <a href="{{ route('contact') }}" class="text-amber-400 hover:underline">Contact page</a>.
                </p>

            </div>
        </div>
    </div>

@endsection
