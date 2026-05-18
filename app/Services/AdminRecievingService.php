<?php

namespace App\Services;

use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminRecievingService
{
    public function recieve(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:inventories,id',
        ]);

        $inventory = Inventory::findOrFail($request->id);
        $user = Auth::user();

        if (!$inventory->received_by) {
            $inventory->received_by = $user->name;
            $inventory->received_date = now();
            $inventory->disposal_status = 'Received by Admin';
            $inventory->save();
        }

        return true;
    }
}
