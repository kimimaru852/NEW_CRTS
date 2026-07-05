<?php

namespace App\Services\API\Admin\CostCenter;

use App\Models\Offices;
use Illuminate\Http\Request;

class AdminDeleteCostCenterService
{
    public function destroy(Request $request, Offices $office)
    {
        // Validate password before deleting
        $request->validateWithBag('officeDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Delete the office
        $office->delete();

        return redirect()->route('admin.office')->with('success', 'Department deleted successfully!');
    }
}
