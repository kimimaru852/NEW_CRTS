<?php

namespace App\Services;

use App\Models\Inventory;

class CheckUpdateInventoryService
{
    public function checkUpdate(int $id, array $data): bool
    {
        // dd($data);

        $inventory = Inventory::findOrFail($id);

        $inventory->update([
            'rack_no' => (int) $data['rack_no'], 
            'loc_code' => $data['loc_code'],
        ]);

        return true;
    }
}
