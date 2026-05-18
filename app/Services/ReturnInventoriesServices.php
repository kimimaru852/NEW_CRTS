<?php

namespace App\Services;

use App\Models\Inventory;

class ReturnInventoriesServices
{
    public function returnInventory(int $inventoryId): Inventory
    {
        $inventory = Inventory::findOrFail($inventoryId);

        $inventory->update([
            'disposal_status' => 'Returned',
            'manager_approval' => NULL,
            'verified_by' => NULL,
            'approved_date' => NULL,
            'received_by' => null,
            $inventory->received_date = null,
        ]);

        $inventory->save();
        
        return $inventory;
    }
}
