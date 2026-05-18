<?php

namespace App\Services;

use App\Models\Offices;
use Illuminate\Http\Request;

class UpdateOfficeService
{
    public function update(Request $request, Offices $office)
    {
        // Validation
        $validated = $request->validate([
            'department' => ['required', 'string', 'max:255'],
        ]);

        // Update office
        $office->update([
            'department' => $validated['department'],
        ]);

        return ['success' => true];
    }
}
