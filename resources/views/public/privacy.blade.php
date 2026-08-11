@extends('layouts.public')

@section('title', 'Privacy Policy')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <div class="mb-10">
            <h1 class="text-white text-3xl lg:text-4xl font-bold mb-3">Privacy Policy</h1>
            <p class="text-gray-500 text-sm">Last updated: {{ date('F j, Y') }}</p>
        </div>

        <div class="glass rounded-3xl p-8 lg:p-10">
            <div class="prose prose-invert max-w-none text-gray-400 text-sm leading-relaxed space-y-6">

                <p>
                    {{ config('app.name') }} ("we", "us", "our") respects your privacy and is committed to
                    protecting your personal data. This Privacy Policy explains what information we collect, how we
                    use it, who we share it with, and the rights you have over your information when you use our
                    platform.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">1. Information We Collect</h2>
                <p>We collect the following categories of information:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong class="text-white">Account information:</strong> name, email address, phone number, and
                        organization name (for Organizers), collected when you register.</li>
                    <li><strong class="text-white">Ticket purchase information:</strong> name, email address, and phone
                        number of Attendees, collected at checkout to generate and deliver tickets.</li>
                    <li><strong class="text-white">Event information:</strong> details Organizers provide when creating an
                        event, including event descriptions, images, ticket pricing, and online meeting links (for virtual
                        or hybrid events).</li>
                    <li><strong class="text-white">Payment information:</strong> we do not collect or store your full card
                        number, CVV, or banking PIN. Payments are processed directly by our payment partners (Paystack and,
                        where applicable, Monnify), who handle this data under their own security and compliance standards.
                        We do retain transaction references and amounts for order and accounting purposes.</li>
                    <li><strong class="text-white">Bank account details:</strong> Organizers who wish to receive payouts
                        provide bank account information, which we use solely to facilitate commission payouts.</li>
                    <li><strong class="text-white">Technical information:</strong> IP address, browser type, device
                        information, and session data, collected automatically for security and platform functionality.</li>
                </ul>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">2. How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Create and manage your account, including verifying your email via a one-time passcode (OTP).</li>
                    <li>Process ticket purchases, generate ticket codes and QR codes, and deliver them to you by email.</li>
                    <li>Facilitate communication between Organizers and Attendees, including sending event updates, WhatsApp
                        group links, or meeting links for online/hybrid events.</li>
                    <li>Process commission payouts to Organizers' registered bank accounts.</li>
                    <li>Send transactional emails, such as order confirmations, event notifications, and account-related
                        messages.</li>
                    <li>Detect, investigate, and prevent fraud, abuse, or violations of our Terms & Conditions.</li>
                    <li>Improve and maintain the security, reliability, and functionality of the platform.</li>
                </ul>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">3. Who We Share Your Information With</h2>
                <p>We share information only where necessary to operate the platform:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong class="text-white">Payment processors:</strong> Paystack and Monnify, to process your
                        payment securely.</li>
                    <li><strong class="text-white">Event Organizers:</strong> if you purchase a ticket, the Organizer of
                        that event receives your name, email, and phone number to manage attendance and communicate event
                        details.</li>
                    <li><strong class="text-white">Service providers:</strong> email delivery providers and other
                        infrastructure providers that help us operate the platform, bound by confidentiality obligations.
                    </li>
                    <li><strong class="text-white">Legal or regulatory authorities:</strong> where required by law, court
                        order, or to protect the rights, property, or safety of {{ config('app.name') }}, our users, or the
                        public.</li>
                </ul>
                <p>We do not sell your personal information to third parties.</p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">4. Data Retention</h2>
                <p>
                    We retain your personal information for as long as your account remains active, or as needed to
                    provide you services, comply with legal obligations, resolve disputes, and enforce our
                    agreements. Accounts that remain unverified for an extended period may be automatically removed
                    from our systems.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">5. Data Security</h2>
                <p>
                    We implement reasonable technical and organizational measures to protect your information,
                    including encrypted password storage, hashed one-time verification codes, and secure session
                    handling. However, no method of transmission or storage is completely secure, and we cannot
                    guarantee absolute security of your information.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">6. Your Rights</h2>
                <p>
                    Under the Nigeria Data Protection Act, you have the right to:
                </p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Access the personal information we hold about you.</li>
                    <li>Request correction of inaccurate or incomplete information.</li>
                    <li>Request deletion of your personal information, subject to our legal and legitimate business
                        retention needs.</li>
                    <li>Object to or restrict certain processing of your information.</li>
                    <li>Withdraw consent where processing is based on consent.</li>
                </ul>
                <p>
                    To exercise any of these rights, please reach out via our
                    <a href="{{ route('contact') }}" class="text-amber-400 hover:underline">Contact page</a>.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">7. Cookies</h2>
                <p>
                    We use session cookies that are necessary for the platform to function, such as keeping you
                    logged in and maintaining your checkout session. We do not currently use third-party advertising
                    or tracking cookies.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">8. Children's Privacy</h2>
                <p>
                    {{ config('app.name') }} is not intended for use by individuals under the age of 18 without the
                    involvement of a parent or legal guardian. We do not knowingly collect personal information from
                    children.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">9. Changes to This Policy</h2>
                <p>
                    We may update this Privacy Policy from time to time to reflect changes in our practices or for
                    legal, operational, or regulatory reasons. We will notify you of material changes via email or a
                    notice on the platform.
                </p>

                <h2 class="text-white font-semibold text-lg mt-8 mb-3">10. Contact Us</h2>
                <p>
                    If you have any questions or concerns about this Privacy Policy or how your information is
                    handled, please reach out via our
                    <a href="{{ route('contact') }}" class="text-amber-400 hover:underline">Contact page</a>.
                </p>

            </div>
        </div>
    </div>

@endsection
