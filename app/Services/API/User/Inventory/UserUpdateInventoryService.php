<?php

namespace App\Services\API\User\Inventory;

use App\Models\Inventory;
use Carbon\Carbon;

class UserUpdateInventoryService
{
    public function update(array $data, $id)
    {
        $inventory = Inventory::with('items')->findOrFail($id);
        $inventory->update([
            'office_origin'   => $data['office_origin'] ?? null,
            'prepared_by'     => $data['prepared_by'] ?? null,
            'disposal_status' => 'For Inventory',
        ]);
        $existingItemIds = $inventory->items->pluck('id')->toArray();
        $incomingItemIds = collect($data['items'])->pluck('id')->filter()->toArray();

        $toDelete = array_diff($existingItemIds, $incomingItemIds);
        if (!empty($toDelete)) {
            $inventory->items()->whereIn('id', $toDelete)->delete();
        }
        
        foreach ($data['items'] as $itemData) {
            $itemData['disposal_date'] = $this->computeDisposalDate($itemData);

            if (!empty($itemData['id'])) {
                // Update existing item
                $item = $inventory->items()->find($itemData['id']);
                if ($item) $item->update($itemData);
            } else {
                // Create new item
                $inventory->items()->create($itemData);
            }
        }

        return $inventory;
    }


    private function computeDisposalDate(array $itemData)
    {
        if (empty($itemData['doc_date'])) {
            $itemData['retention_period'] = null;
            return null;
        }

        $docDate = Carbon::parse($itemData['doc_date']);

        // Rule: if status is Permanent, skip retention
        if (!empty($itemData['status']) && strtolower($itemData['status']) === 'permanent') {
            return null;
        }

        // Otherwise, compute with retention period
        $retention = (int) ($itemData['retention_period'] ?? 0);

        return $retention > 0 ? $docDate->copy()->addYears($retention) : null;
    }
}