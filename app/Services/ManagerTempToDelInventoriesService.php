<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\ArchiveInventories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManagerTempToDelInventoriesService
{
    /**
     * Get all countries.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function toArchiveInventoryAndDelete($inventoryId)
{
    $inventory = Inventory::with('items')->findOrFail($inventoryId);
    $user = Auth::user();

    if (!$inventory->manager_approval) {
            $inventory->manager_approval = $user->name ;
            $inventory->save();
        }

    DB::transaction(function() use ($inventory) {
        $inventory->disposal_status = 'disposed';
        $inventory->save();
        $archivedData = $inventory->only([
            'office_origin',
            'prepared_by',
            'list_no',
            'loc_code',
            'received_by',
            'received_date',
            'manager_approval',
            'verified_by',
            'verified_date',
            'disposed_date',
            'user_id',
            'office_id',
            'rack_no',
        ]);
        $archivedData['disposal_status'] = 'Rejected by Head';

        $archived = ArchiveInventories::create($archivedData);

        // Archive items
        foreach ($inventory->items as $item) {
            $archived->items()->create([
                'item_no' => $item->item_no,
                'description' => $item->description,
                'doc_date' => $item->doc_date,
                'quantity_code' => $item->quantity_code,
                'index_code' => $item->index_code,
                'status' => $item->status,
                'retention_period' => $item->retention_period,
                'disposal_date' => $item->disposal_date,
                'rds_no' => $item->rds_no,
            ]);
        }
        

        // Delete original
        $inventory->items()->delete();
        $inventory->delete();
    });
}
}
