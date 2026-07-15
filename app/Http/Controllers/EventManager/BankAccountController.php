<?php

namespace App\Http\Controllers\EventManager;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccount = BankAccount::where('user_id', Auth::id())->first();

        return view('eventmanager.account', compact('bankAccount'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bank_name' => ['required', 'string'],
            'account_number' => ['required', 'string', 'size:10'],
            'account_name' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'in:NGN,GHS,KES,ZAR,GBP'],
        ]);

        BankAccount::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'currency' => $request->currency,
            ]
        );

        return back()->with('success', 'Bank account saved successfully!');
    }
}
