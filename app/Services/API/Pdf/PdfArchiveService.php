<?php

namespace App\Services\API\Pdf;

use App\Models\ArchiveInventories;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class PdfArchiveService
{
    public function generate(Request $request, $id)
    {
        $inventory = ArchiveInventories::with('items', 'user')->findOrFail($id);

        $names = [
            'prepared_by' => $inventory->prepared_by,
            'manager_approval' => $inventory->manager_approval,
            'verified_by' => $inventory->verified_by,
        ];

        $users = User::whereIn('name', array_values($names))
            ->get()
            ->keyBy('name');

        $prepared_by = $users[$names['prepared_by']] ?? null;
        $manager_approval = $users[$names['manager_approval']] ?? null;
        $verified_by = $users[$names['verified_by']] ?? null;

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
            'prepared_by' => $prepared_by,
            'manager_approval' => $manager_approval,
            'verified_by' => $verified_by,
            'received_date' => $received_date,
            'verified_date' => $verified_date,
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            "TransCo_Archive_File-RTO_Inventory List Form - Box No._{$id}.pdf"
        );
    }
}
