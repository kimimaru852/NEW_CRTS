<?php

namespace App\Services\API\Admin\Inventory;

use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminVerifyService
{
    public function verify(Request $request): bool
    {
        $request->validate([
            'id' => 'required|exists:inventories,id',
        ]);

        $inventory = Inventory::findOrFail($request->id);
        $user = Auth::user();

        if (is_null($inventory->received_by)) {
            throw new \Exception('You cannot approve an item that has not been received.');
        }

        if (!$inventory->verified_by) {
            $inventory->verified_by = $user->name;
            $inventory->verified_date = now();
            $inventory->disposal_status = 'Verified by Admin';
            $inventory->save();
        }

        return true;
    }
}
