<?php 

namespace App\Services\API\Admin\CostCenter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Offices;

class AdminCreateCostCenterService
{
    public function create(Request $request)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'department' => 'required|string|max:255',
        ]);

        // validation checker
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // checker
        $existingOffice = Offices::where('department', $request->department)->first();
        if ($existingOffice) {
            return redirect()->back()
                ->withErrors(['department' => 'Cost Center already exists.'])
                ->withInput();
        }
        // Create the office
        Offices::create(['department' => $request->department]);

        return redirect()->back()->with('success', 'Cost Center created successfully.');
    }
}