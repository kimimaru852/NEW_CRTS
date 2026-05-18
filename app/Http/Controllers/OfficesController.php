<?php

namespace App\Http\Controllers;

use App\Models\Offices;
use App\Services\CreateOfficeService;
use App\Services\DeleteOfficeService;
use App\Services\DisplayOfficesServices;
use Illuminate\Http\Request;
use App\Services\UpdateOfficeService;

class OfficesController extends Controller
{
    protected $createOfficeService;
    protected $displayOfficesService;
    protected $deleteOfficeService;
    protected $updateOfficeService;

    public function __construct(
        CreateOfficeService $createOfficeService,
        DisplayOfficesServices $displayOfficesService,
        DeleteOfficeService $deleteOfficeService,
        UpdateOfficeService $updateOfficeService
    ) {
        $this->createOfficeService = $createOfficeService;
        $this->displayOfficesService = $displayOfficesService;
        $this->deleteOfficeService = $deleteOfficeService;
        $this->updateOfficeService = $updateOfficeService;
    }

    public function storeOffice(Request $request)
    {
        return $this->createOfficeService->createOffice($request);
    }

    public function fetchOfficeSelection()
    {
        $offices = $this->displayOfficesService->display();

        return view('admin.register', compact('offices'));
    }

    public function displayOffice()
    {
        $offices = $this->displayOfficesService->display();

        return view('admin.office', compact('offices'));
    }

    public function destroyOffice(Request $request, Offices $office)
    {
        return $this->deleteOfficeService->delete($request, $office);
    }

    public function updateOffice(Request $request, Offices $office)
    {
        $result = $this->updateOfficeService->update($request, $office);

        return response()->json($result);
    }
}
