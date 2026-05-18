<?php

namespace App\Services;

use App\Models\ArchiveInventories;
use App\Models\Inventory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrintInventoryService
{
    /**
     * Get all countries.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    
    public function generatePdf(Request $request, $id)
    {
        $inventory = Inventory::with('items')->findOrFail($id);

        $received_date = null;
        $verified_date = null;

        if ($request->has('received_date')) {
            $received_date = Carbon::createFromFormat('Ymd', $request->received_date);
        }

        if ($request->has('verified_date')) {
            $verified_date = Carbon::createFromFormat('Ymd', $request->verified_date);
        }

        $pdf = Pdf::loadView('pdf.inventory-pdf', [
            'inventory' => $inventory,
            'received_date' => $received_date,
            'verified_date' => $verified_date,
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            "TransCo_Active_File-RTO_Inventory List Form - Box No._{$id}.pdf"
        );
    }

    public function generateArchPdf(Request $request, $id)
    {
        $inventory = ArchiveInventories::with('items')->findOrFail($id);

        $received_date = null;
        $verified_date = null;

        if ($request->has('received_date')) {
            $received_date = Carbon::createFromFormat('Ymd', $request->received_date);
        }

        if ($request->has('verified_date')) {
            $verified_date = Carbon::createFromFormat('Ymd', $request->verified_date);
        }

        $pdf = Pdf::loadView('pdf.inventory-pdf', [
            'inventory' => $inventory,
            'received_date' => $received_date,
            'verified_date' => $verified_date,
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            "TransCo_Archive_File-RTO_Inventory List Form - Box No._{$id}.pdf"
        );
    }
}
