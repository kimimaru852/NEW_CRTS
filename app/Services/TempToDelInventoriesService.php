<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\ArchiveInventories;
use Illuminate\Support\Facades\DB;

class TempToDelInventoriesService
{
    /**
     * Get all countries.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function toArchiveInventoryAndDelete($inventoryId)
{
    $inventory = Inventory::with('items')->findOrFail($inventoryId);

    DB::transaction(function() use ($inventory) {
        $inventory->disposal_status = 'disposed';
        $inventory->save();
        $archivedData = $inventory->only([
            'office_origin',
            'prepared_by',
            'list_no',
            'loc_code',
            'recieved_by',
            'recieved_date',
            'manager_approval',
            'verified_by',
            'verified_date',
            'disposed_date',
            'user_id',
            'office_id',
            'rack_no',
        ]);
        $archivedData['disposal_status'] = 'Rejected by Admin';

        $archived = ArchiveInventories::create($archivedData);

        // Archive items
        foreach ($inventory->items as $item) {
            $archived->items()->create([
                'item_no' => $item->item_no,
                'description' => $item->description,
                'doc_date' => $item->doc_date,
                'unit_code' => $item->unit_code,
                'quantity' => $item->quantity,
                'document_status' => $item->document_status,
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
