<?php

namespace App\Http\Controllers;

use App\Models\Offices;
use Illuminate\Http\Request;
use App\Services\API\Admin\CostCenter\AdminCreateCostCenterService;
use App\Services\API\Admin\CostCenter\AdminDeleteCostCenterService;
use App\Services\API\Admin\CostCenter\AdminDisplayCostCenterService;
use App\Services\API\Admin\CostCenter\AdminUpdateCostCenterService;



class OfficesController extends Controller
{
    protected $adminCreateCostCenterService;
    protected $admindisplayCostCenterService;
    protected $adminDeleteCostCenterService;
    protected $adminUpdateCostCenterService;

    public function __construct(
        AdminCreateCostCenterService $adminCreateCostCenterService,
        AdminDisplayCostCenterService $admindisplayCostCenterService,
        AdminDeleteCostCenterService $adminDeleteCostCenterService,
        AdminUpdateCostCenterService $adminUpdateCostCenterService
    ) {
        $this->adminCreateCostCenterService = $adminCreateCostCenterService;
        $this->admindisplayCostCenterService = $admindisplayCostCenterService;
        $this->adminDeleteCostCenterService = $adminDeleteCostCenterService;
        $this->adminUpdateCostCenterService = $adminUpdateCostCenterService;
    }

    public function storeOffice(Request $request)
    {
        return $this->adminCreateCostCenterService->create($request);
    } 

    public function fetchOfficeSelection()
    {
        $offices = $this->admindisplayCostCenterService->display();

        return view('admin.register', compact('offices'));
    }

    public function displayOffice()
    {
        $offices = $this->admindisplayCostCenterService->display();

        return view('admin.office', compact('offices'));
    }

    public function destroyOffice(Request $request, Offices $office)
    {
        return $this->adminDeleteCostCenterService->destroy($request, $office);
    }

    public function updateOffice(Request $request, Offices $office)
    {
        $result = $this->adminUpdateCostCenterService->update($request, $office);

        return response()->json($result);
    }
}
