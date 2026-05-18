<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateInventoryService
{
    //
    public function createInventory(Request $request)
    {
        DB::beginTransaction();
        $user = Auth::user();

        try {
            // count the number of valid inventory item
            $validItems = collect($request->items)->filter(function ($item) {
                return !empty($item['description']) &&
                    !empty($item['doc_date']) &&
                    !empty($item['unit_code']) && // just check not empty
                    !empty($item['quantity']) &&
                    !empty($item['rds_no']) &&
                    (!empty($item['retention_period']) || strtolower($item['document_status']) === 'permanent');
            });

            $itemCount = $validItems->count();

            // Create the main inventory
            $inventory = Inventory::create([
                'office_origin' => $user->office->department ?? 'Unknown Office',
                'prepared_by' => $user->name,
                'list_no' => $itemCount,
                'disposal_status' => 'For Inventory',
                'user_id' => $user->id,
                'office_id' => $user->office_id,
            ]);

            // Store each valid inventory item
            foreach ($validItems->values() as $index => $item) {
                $isPermanent = strtolower($item['document_status']) === 'permanent';

                $docDate = Carbon::parse($item['doc_date']);
                $retention = $isPermanent ? null : (int) $item['retention_period'];
                $disposalDate = $isPermanent ? null : $docDate->copy()->addYears($retention);

                $inventory->items()->create([
                    'item_no' => $index + 1,
                    'description' => $item['description'],
                    'doc_date' => $docDate,
                    'unit_code' => $item['unit_code'],
                    'quantity' => $item['quantity'],
                    'rds_no' => $item['rds_no'],
                    'document_status' => $item['document_status'],
                    'retention_period' => $retention,
                    'disposal_date' => $disposalDate,
                ]);
            }

            DB::commit();

            return redirect()->route('user.index')
                ->with('success', 'Inventory and ' . $itemCount . ' items saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save inventory. ' . $e->getMessage());
        }
    }
}
