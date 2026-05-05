<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $globalRate = config('app.commission_rate', 5);
        $managers = User::where('role', 'event_manager')
            ->where('is_active', true)
            ->get();

        return view('admin.commission', compact('globalRate', 'managers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        return back()->with('success', 'Commission rate updated successfully.');
    }
}
