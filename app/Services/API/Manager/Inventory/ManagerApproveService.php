<?php

namespace App\Services\API\Manager\Inventory;

use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ManagerApproveService
{
    public function approve(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:inventories,id',
        ]);

        $inventory = Inventory::findOrFail($request->id);
        $user = Auth::user();

        // Approve only if not already approved
        if (!$inventory->manager_approval) {
            $inventory->manager_approval = $user->name;
            $inventory->manager_approval_date = now();
            $inventory->save();
        }

        return true;
    }
}
