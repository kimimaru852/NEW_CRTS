<?php

namespace App\Services\API\Admin\GrdsList;

use App\Models\GrdsLists;
use Illuminate\Http\Request;

class CreateGRDSList
{
    public function create(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'grds_rds_no' => 'required|string',
            'retention_period' => 'nullable|integer|required_if:document_status,Temporary',
            'document_status' => 'required|string|in:Permanent,Temporary'
        ]);

        $existingList = GrdsLists::where('description', $request->description)
            ->first();
            
        if ($existingList) {
            return redirect()->back()
                ->withErrors([
                    'description' => 'This list already exists'
                ])
                ->withInput();
        }

        GrdsLists::create([
            'description' => $request->description,
            'grds_rds_no' => $request->grds_rds_no,
            'retention_period' => $request->retention_period,
            'document_status' => $request->document_status,
        ]);

        return redirect()->back()
            ->with('success', 'GRDS list created successfully.');
    }
}
