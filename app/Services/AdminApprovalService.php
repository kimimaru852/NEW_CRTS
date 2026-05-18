<?php

namespace App\Services;

use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminApprovalService
{
    /**
     * Get all database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    
    public function approve(Request $request): bool
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
