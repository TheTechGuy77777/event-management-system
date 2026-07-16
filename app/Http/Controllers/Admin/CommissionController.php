<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommissionController extends Controller
{
    public function index()
    {
        $globalRate = Cache::get('commission_rate', config('app.commission_rate', 5));
        $managers = User::where('role', 'event_manager')
            ->where('is_active', true)
            ->get();

        return view('admin.commission', compact('globalRate', 'managers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'manager_id' => ['nullable', 'exists:users,id'],
        ]);

        if ($request->filled('manager_id')) {
            User::where('id', $request->manager_id)->update([
                'custom_commission' => $request->commission_rate,
            ]);
        } else {
            Cache::forever('commission_rate', $request->commission_rate);
        }

        return back()->with('success', 'Commission rate updated successfully.');
    }
}
