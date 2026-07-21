@extends('layouts.public')

@section('title', 'Verify Email — EventPlug')

@section('content')

    <style>
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-6px);
            }

            40%,
            80% {
                transform: translateX(6px);
            }
        }

        .animate-shake {
            animation: shake 0.35s ease-in-out;
        }
    </style>

    <div x-data="otpVerification({
        verifyUrl: '{{ route('verification.otp.verify', ['token' => $token]) }}',
        resendUrl: '{{ route('verification.otp.resend', ['token' => $token]) }}',
        expiresInSeconds: {{ $expiresInSeconds }},
        resendAvailableInSeconds: {{ $resendAvailableInSeconds }},
    })" class="min-h-screen flex items-center justify-center px-4">

        <div class="glass rounded-3xl p-8 w-full max-w-md">

            <h1 class="text-white text-2xl font-bold text-center mb-3">
                Verify your email
            </h1>

            <p class="text-gray-500 text-sm text-center mb-2">
                Enter the 6-digit code sent to your email address.
            </p>

            <p class="text-center text-xs mb-4">
                <template x-if="!expired">
                    <span class="text-gray-500">Expires in <span class="text-white font-medium"
                            x-text="formattedExpiry"></span></span>
                </template>
                <template x-if="expired">
                    <span class="text-red-400 font-medium">Code expired. Request a new one below.</span>
                </template>
            </p>

            <div x-show="message" x-transition :class="messageType === 'error' ? 'text-red-400' : 'text-green-400'"
                class="text-sm mb-4 text-center" x-text="message"></div>

            <div class="flex justify-center gap-2 mb-1" :class="{ 'animate-shake': shake }"
                @paste.prevent="handlePaste($event)">
                <template x-for="(digit, index) in digits" :key="index">
                    <input type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code"
                        :id="'otp-box-' + index" x-model="digits[index]" @input="onDigitInput(index, $event)"
                        @keydown.backspace="onBackspace(index, $event)" @keydown.arrow-left="focusBox(index - 1)"
                        @keydown.arrow-right="focusBox(index + 1)" :disabled="expired || locked || verifying"
                        :class="digit ? 'border-amber-500/60 bg-white/10' : 'border-white/10 bg-white/5'"
                        class="w-12 h-14 text-center text-2xl font-semibold rounded-xl border text-white outline-none transition-all duration-150 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/30 disabled:opacity-40">
                </template>
            </div>

            <button @click="submit()" :disabled="verifying || expired || locked || !isComplete" type="button"
                class="btn-gold w-full mt-5 py-3 rounded-xl font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!verifying">Verify Email</span>
                <span x-show="verifying">Verifying...</span>
            </button>

            <div class="mt-4">
                <button @click="resend()" type="button" :disabled="resending || resendCountdown > 0" id="resend-button"
                    class="text-sm text-amber-500 hover:text-amber-400 disabled:text-gray-500 disabled:cursor-not-allowed">
                    <span x-show="resendCountdown > 0" x-text="'Resend code in ' + resendCountdown + 's'"></span>
                    <span x-show="resendCountdown <= 0 && !resending">Resend verification code</span>
                    <span x-show="resending">Sending...</span>
                </button>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('otpVerification', (config) => ({
                verifyUrl: config.verifyUrl,
                resendUrl: config.resendUrl,
                digits: ['', '', '', '', '', ''],
                shake: false,
                expiresInSeconds: config.expiresInSeconds,
                resendCountdown: config.resendAvailableInSeconds,
                expired: config.expiresInSeconds <= 0,
                locked: false,
                verifying: false,
                resending: false,
                message: '',
                messageType: 'error',

                init() {
                    this.$nextTick(() => this.focusBox(0));

                    this._expiryTimer = setInterval(() => {
                        if (this.expiresInSeconds > 0) {
                            this.expiresInSeconds--;
                        } else {
                            this.expired = true;
                            clearInterval(this._expiryTimer);
                        }
                    }, 1000);

                    this._resendTimer = setInterval(() => {
                        if (this.resendCountdown > 0) this.resendCountdown--;
                    }, 1000);
                },

                get formattedExpiry() {
                    const m = Math.floor(this.expiresInSeconds / 60);
                    const s = this.expiresInSeconds % 60;
                    return `${m}:${s.toString().padStart(2, '0')}`;
                },

                get code() {
                    return this.digits.join('');
                },

                get isComplete() {
                    return this.digits.every((d) => d !== '');
                },

                focusBox(index) {
                    if (index < 0 || index > 5) return;
                    document.getElementById('otp-box-' + index)?.focus();
                },

                onDigitInput(index, event) {
                    const value = event.target.value.replace(/[^0-9]/g, '');
                    this.digits[index] = value.slice(-1);

                    if (value && index < 5) {
                        this.focusBox(index + 1);
                    }

                    if (this.isComplete) {
                        this.submit();
                    }
                },

                onBackspace(index, event) {
                    if (this.digits[index] === '' && index > 0) {
                        this.focusBox(index - 1);
                    }
                },

                handlePaste(event) {
                    const pasted = (event.clipboardData.getData('text') || '').replace(/[^0-9]/g, '')
                        .slice(0, 6);
                    if (!pasted) return;

                    this.digits = pasted.split('').concat(Array(6 - pasted.length).fill(''));
                    this.focusBox(Math.min(pasted.length, 6) - 1);

                    if (this.isComplete) {
                        this.submit();
                    }
                },

                triggerShake() {
                    this.shake = true;
                    setTimeout(() => {
                        this.shake = false;
                    }, 350);
                },

                async submit() {
                    if (this.verifying || this.expired || this.locked || !this.isComplete) return;

                    this.verifying = true;
                    this.message = '';

                    try {
                        const res = await fetch(this.verifyUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                Accept: 'application/json',
                            },
                            body: JSON.stringify({
                                code: this.code
                            }),
                        });

                        const data = await res.json();

                        if (!res.ok) {
                            this.messageType = 'error';
                            this.message = data.message;
                            this.digits = ['', '', '', '', '', ''];
                            this.triggerShake();

                            if (res.status === 429) {
                                this.locked = true;
                            } else {
                                this.focusBox(0);
                            }
                            return;
                        }

                        this.messageType = 'success';
                        this.message = data.message;
                        clearInterval(this._expiryTimer);
                        clearInterval(this._resendTimer);
                        window.location.href = data.redirect;
                    } catch (e) {
                        this.messageType = 'error';
                        this.message = 'Something went wrong. Please try again.';
                    } finally {
                        this.verifying = false;
                    }
                },

                async resend() {
                    if (this.resending || this.resendCountdown > 0) return;

                    this.resending = true;
                    this.message = '';

                    try {
                        const res = await fetch(this.resendUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                Accept: 'application/json',
                            },
                        });

                        const data = await res.json();

                        if (!res.ok) {
                            this.messageType = 'error';
                            this.message = data.message;
                            if (data.resendAvailableInSeconds) this.resendCountdown = data
                                .resendAvailableInSeconds;
                            return;
                        }

                        this.messageType = 'success';
                        this.message = data.message;
                        this.expiresInSeconds = data.expiresInSeconds;
                        this.resendCountdown = data.resendAvailableInSeconds;
                        this.expired = false;
                        this.locked = false;
                        this.digits = ['', '', '', '', '', ''];
                        this.$nextTick(() => this.focusBox(0));
                    } catch (e) {
                        this.messageType = 'error';
                        this.message = 'Could not resend code. Please try again.';
                    } finally {
                        this.resending = false;
                    }
                },
            }));
        });
    </script>
@endpush
