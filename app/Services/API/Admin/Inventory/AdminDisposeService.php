<?php

namespace App\Services\API\Admin\Inventory;

use App\Models\Inventory;
use App\Models\ArchiveInventories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDisposeService
{
    public function dispose($inventoryId, Request $request)
    {
        $inventory = Inventory::with('items')->findOrFail($inventoryId);

        DB::transaction(function () use ($inventory, $request) {
            // Update disposal status and date
            $inventory->disposal_status = 'disposed';
            $inventory->disposed_date = $request->disposed_date;
            $inventory->nap_authority_no = $request->nap_authority_no;

            // Create archived inventory
            $archived = ArchiveInventories::create($inventory->only([
                'office_origin',
                'prepared_by',
                'list_no',
                'loc_code',
                'nap_authority_no',
                'received_by',
                'received_date',
                'manager_approval',
                'manager_approval_date',
                'verified_by',
                'verified_date',
                'disposal_status',
                'disposed_date',
                'user_id',
                'office_id',
                'rack_no',
            ]));

            // Archive related items
            foreach ($inventory->items as $item) {
                $archived->items()->create([
                    'item_no' => $item->item_no,
                    'description' => $item->description,
                    'doc_date' => $item->doc_date,
                    'quantity' => $item->quantity,
                    'unit_code' => $item->unit_code,
                    'document_status' => $item->document_status,
                    'retention_period' => $item->retention_period,
                    'disposal_date' => $item->disposal_date,
                    'rds_no' => $item->rds_no,
                    // 'archive_inventories_id' => $item->archive_inventories_id,
                ]);
            }

            // Delete original
            $inventory->items()->delete();
            $inventory->delete();
        });

        return [
            'success' => true,
            'message' => 'Inventory disposed and archived successfully.'
        ];
    }
}
