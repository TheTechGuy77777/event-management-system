@extends('layouts.dashboard')

@section('title', 'Bank Account')
@section('page-title', 'Bank Account')
@section('page-subtitle', 'Manage your payout bank details')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Bank Account Form -->
        <div class="lg:col-span-2">
            <div class="glass rounded-2xl p-8">
                <h2 class="text-white font-semibold mb-2 flex items-center gap-2">
                    <div class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-building-columns text-black text-xs"></i>
                    </div>
                    Payout Bank Details
                </h2>
                <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                    Add your bank account to receive payouts from ticket sales. All earnings will be transferred to this
                    account.
                </p>

                <form method="POST" action="{{ route('dashboard.account.update') }}" class="space-y-5">
                    @csrf
                    @method('POST')

                    <!-- Bank Name -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Bank Name <span class="text-amber-400">*</span>
                        </label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-building-columns absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <select name="bank_name" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 appearance-none cursor-pointer @error('bank_name') border-red-500/50 @enderror">
                                <option value="">Select your bank</option>
                                @foreach (['Access Bank', 'Citibank', 'Ecobank', 'Fidelity Bank', 'First Bank', 'First City Monument Bank (FCMB)', 'Globus Bank', 'Guaranty Trust Bank (GTBank)', 'Heritage Bank', 'Keystone Bank', 'Kuda Bank', 'Opay', 'PalmPay', 'Polaris Bank', 'Providus Bank', 'Stanbic IBTC Bank', 'Standard Chartered', 'Sterling Bank', 'SunTrust Bank', 'Union Bank', 'United Bank for Africa (UBA)', 'Unity Bank', 'Wema Bank', 'Zenith Bank'] as $bank)
                                    <option value="{{ $bank }}"
                                        {{ old('bank_name', $bankAccount->bank_name ?? '') == $bank ? 'selected' : '' }}>
                                        {{ $bank }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bank_name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Number -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Account Number <span class="text-amber-400">*</span>
                        </label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="text" name="account_number" id="account_number"
                                value="{{ old('account_number', $bankAccount->account_number ?? '') }}"
                                placeholder="0123456789" maxlength="10" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 @error('account_number') border-red-500/50 @enderror">
                        </div>
                        @error('account_number')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Name -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Account Name <span class="text-amber-400">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="text" name="account_name" id="account_name"
                                value="{{ old('account_name', $bankAccount->account_name ?? '') }}"
                                placeholder="Account name will appear here" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-white placeholder-gray-600 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 @error('account_name') border-red-500/50 @enderror">
                        </div>
                        <p class="text-gray-600 text-xs mt-1">
                            Enter your account name exactly as it appears on your bank statement.
                        </p>
                        @error('account_name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Currency -->
                    <div>
                        <label class="text-gray-400 text-sm font-medium mb-2 block">
                            Currency <span class="text-amber-400">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-coins absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <select name="currency" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-gray-300 text-sm focus:outline-none focus:border-amber-400/50 transition-all duration-200 appearance-none cursor-pointer">
                                @foreach ([
            'NGN' => 'NGN — Nigerian Naira',
            'GHS' => 'GHS — Ghanaian Cedi',
            'KES' => 'KES — Kenyan Shilling',
            'ZAR' => 'ZAR — South African Rand',
            'GBP' => 'GBP — British Pound',
        ] as $code => $label)
                                    <option value="{{ $code }}"
                                        {{ old('currency', $bankAccount->currency ?? 'NGN') == $code ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-gold px-8 py-3 rounded-xl text-black font-semibold text-sm">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>Save Bank Account
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Current Account -->
            <div class="glass rounded-2xl p-6">
                <h3 class="text-white font-semibold text-sm mb-4">
                    <i class="fa-solid fa-circle-check text-amber-400 mr-2"></i>
                    Current Account
                </h3>

                @if (isset($bankAccount) && $bankAccount)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 text-xs">Bank</span>
                            <span class="text-white text-xs font-medium">{{ $bankAccount->bank_name }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 text-xs">Account No.</span>
                            <span class="text-white text-xs font-medium">
                                ****{{ substr($bankAccount->account_number, -4) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 text-xs">Account Name</span>
                            <span class="text-white text-xs font-medium">{{ $bankAccount->account_name }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 text-xs">Currency</span>
                            <span class="text-amber-400 text-xs font-semibold">{{ $bankAccount->currency }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="w-12 h-12 glass rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-building-columns text-amber-400/30 text-xl"></i>
                        </div>
                        <p class="text-gray-500 text-xs">No bank account added yet.</p>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="glass rounded-2xl p-6">
                <h3 class="text-white font-semibold text-sm mb-4">
                    <i class="fa-solid fa-circle-info text-amber-400 mr-2"></i>
                    Payout Information
                </h3>
                <ul class="space-y-3 text-gray-500 text-xs leading-relaxed">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-amber-400 mt-0.5 flex-shrink-0"></i>
                        Payouts are processed after each event ends.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-amber-400 mt-0.5 flex-shrink-0"></i>
                        Platform commission is deducted before transfer.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-amber-400 mt-0.5 flex-shrink-0"></i>
                        You can update your bank account at any time.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-amber-400 mt-0.5 flex-shrink-0"></i>
                        Only one bank account can be saved at a time.
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection
