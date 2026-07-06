<?php

namespace App\Services\API\Admin\Inventory;

use App\Models\Inventory;

class AdminUpdateInventoryService
{
    public function checkUpdate(int $id, array $data): bool
    {

        $inventory = Inventory::findOrFail($id);

        $inventory->update([
            'rack_no' => (int) $data['rack_no'], 
            'loc_code' => $data['loc_code'],
        ]);

        return true;
    }
}
