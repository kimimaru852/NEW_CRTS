<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\API\Admin\GrdsList\CreateGRDSList;
use App\Services\API\Admin\GrdsList\DisplayGRDSList;
use App\Services\API\Admin\GrdsList\UpdateGRDSList;
use App\Services\API\Admin\GrdsList\DeleteGRDSList;
use App\Services\API\Admin\GrdsList\PrintGDRSList;

class GrdsListsController extends Controller
{
    protected $createGrdsListService;
    protected $displayGrdsListService;
    protected $updateGrdsListService;
    protected $deleteGrdsListService;
    protected $printGrdsListService;

    public  function __construct(CreateGRDSList $createGrdsListService, DisplayGRDSList $displayGrdsListService, UpdateGRDSList $updateGrdsListService, DeleteGRDSList $deleteGrdsListService, PrintGDRSList $printGrdsListService)
    {
        $this->createGrdsListService = $createGrdsListService;
        $this->displayGrdsListService = $displayGrdsListService;
        $this->updateGrdsListService = $updateGrdsListService;
        $this->deleteGrdsListService = $deleteGrdsListService;
        $this->printGrdsListService = $printGrdsListService;
    }
    //
    public function displayGrdsLists(Request $request)
    {
        if ($request->ajax()) {
            return $this->displayGrdsListService->display();
        }

        return view('admin.grds-rds-lists');
    }

    public function create(Request $request)
    {
        return $this->createGrdsListService->create($request);
    }

    public function update(Request $request, $id)
    {
        $this->updateGrdsListService->update($request->all(), $id);

        return view('admin.grds-rds-lists');
    }

    public function deleteGrdsRds($id)
    {
        $this->deleteGrdsListService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully.'
        ]);
    }
    public function printGdrsList()
    {
        return $this->printGrdsListService->printPdf();
    }
}
