<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\API\Admin\Inventory\AdminVerifyService;
use App\Services\API\Admin\Inventory\AdminDisplayService;
use App\Services\API\Admin\Inventory\AdminRecieveService;
use App\Services\API\Admin\Inventory\AdminUpdateInventoryService;
use App\Services\API\User\Inventory\UserCreateInventoryService;
use App\Services\DeleteInventoryService;
use App\Services\API\Admin\Inventory\AdminDisposeService;
use App\Services\API\Manager\Inventory\ManagerApproveService;
use App\Services\API\Manager\Inventory\ManagerDisplayService;
use App\Services\API\Admin\Inventory\AdminProcessedService;
use App\Services\API\User\Inventory\UserDisplayInventoriesService;
use App\Services\API\User\Inventory\UserCountInventoryService;
use App\Services\API\User\Inventory\UserUpdateInventoryService;
use App\Services\API\Admin\Inventory\AdminReturnService;
use App\Services\API\Admin\GrdsList\DisplayGRDSList;
use App\Services\API\User\Form\ShowSelection;

class InventoryController extends Controller
{

    protected $adminProcessedService;
    protected $adminDisplayService;
    protected $managerDisplayService;
    protected $userDisplayInventoriesService;
    protected $userCountInventoryService;
    protected $userCreateInventoryService;
    protected $adminUpdateInventoryService;
    protected $deleteInventoryService;
    protected $adminRecieveService;
    protected $managerApproveService;
    protected $adminVerifyService;
    protected $adminDisposeService;
    protected $userUpdateInventoryService;
    protected $adminReturnService;
    protected $displayGrdsListService;
    protected $showSelectionService;

    public function __construct(
        AdminProcessedService $adminProcessedService,
        AdminDisplayService $adminDisplayService,
        ManagerDisplayService $managerDisplayService,
        UserDisplayInventoriesService $userDisplayInventoriesService,
        UserCountInventoryService $userCountInventoryService,
        UserCreateInventoryService $userCreateInventoryService,
        AdminUpdateInventoryService $adminUpdateInventoryService,
        DeleteInventoryService $deleteInventoryService,
        AdminRecieveService $adminRecieveService,
        ManagerApproveService $managerApproveService,
        AdminVerifyService $adminVerifyService,
        AdminDisposeService $adminDisposeService,
        UserUpdateInventoryService $userUpdateInventoryService,
        AdminReturnService $adminReturnService,
        DisplayGRDSList $displayGrdsListService,
        ShowSelection $showSelectionService,
    ) {
        $this->adminProcessedService = $adminProcessedService;
        $this->adminDisplayService = $adminDisplayService;
        $this->managerDisplayService = $managerDisplayService;
        $this->userDisplayInventoriesService = $userDisplayInventoriesService;
        $this->userCountInventoryService = $userCountInventoryService;
        $this->userCreateInventoryService = $userCreateInventoryService;
        $this->deleteInventoryService = $deleteInventoryService;
        $this->managerApproveService = $managerApproveService;
        $this->adminRecieveService = $adminRecieveService;
        $this->adminVerifyService = $adminVerifyService;
        $this->adminUpdateInventoryService = $adminUpdateInventoryService;
        $this->adminDisposeService = $adminDisposeService;
        $this->userUpdateInventoryService = $userUpdateInventoryService;
        $this->adminReturnService = $adminReturnService;
        $this->displayGrdsListService = $displayGrdsListService;
        $this->showSelectionService = $showSelectionService;
    }

    public function displaySelection()
    {
        $lists = $this->showSelectionService->showSelection();
        return view('user.form', compact('lists'));
    }

    public function displayRegister()
    {
        return view('manager.register');
    }

    public function create(Request $request)
    {
        return $this->userCreateInventoryService->createInventory($request);
    }

    public function update(Request $request, int $id, AdminUpdateInventoryService $adminUpdateInventoryService)
    {
        $validated = $request->validate([
            'rack_no' => 'required|integer|min:1',
            'loc_code' => 'required|string|max:50',
        ]);

        $adminUpdateInventoryService->checkUpdate($id, $validated);

        return response()
            ->json(['message' => 'Inventory updated successfully.']);
    }

    public function displayIndexUser(Request $request)
    {
        if (request()->ajax()) {
            return $this->userDisplayInventoriesService->display($request);
        }

        $inventories = $this->userCountInventoryService->count();
        $lists = $this->showSelectionService->showSelection();
        //$totalInv = $inventories->count();

        return view('user.index', compact('inventories', 'lists'));
    }

    // index display of admin
    public function adminDisplay(Request $request)
    {
        $data = $this->adminDisplayService->display($request);
        if ($data) return $data;

        return view('admin.index');
    }

    // index display of manager
    public function managerDisplay(Request $request)
    {
        $data = $this->managerDisplayService->display($request);
        if ($data) return $data;

        return view('manager.index');
    }

    // manager approval
    public function approve(Request $request)
    {
        $this->managerApproveService->approve($request);

        return redirect()
            ->route('manager.index')
            ->with('success', 'Inventory approved successfully.');
    }

    // RTO recieve by admin
    public function adminRecieve(Request $request)
    {
        $this->adminRecieveService->recieve($request);

        return redirect()
            ->route('admin.index')
            ->with('success', 'Inventory recieved successfully.');
    }

    // RTO approve by admin
    public function adminApprove(Request $request)
    {
        try {
            $this->adminVerifyService->verify($request);

            return redirect()
                ->route('admin.index')
                ->with('success', 'Inventory Verified successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.index')
                ->with('error', $e->getMessage());
        }
    }

    // Processed RTO By Admin
    public function destroy($inventoryId)
    {
        $this->adminProcessedService->processed($inventoryId);

        return redirect()
            ->back()
            ->with('success', 'Inventory archived successfully.');
    }

    public function returnInventory($inventoryId)
    {
        $this->adminReturnService->returnInventory($inventoryId);

        return redirect()
            ->back()
            ->with('success', 'Inventory has been marked as returned.');
    }

    // archive inventory
    public function destroyArch($id)
    {
        $this->deleteInventoryService->delete($id);

        return redirect()
            ->back()
            ->with('success', 'Inventory archived successfully.');
    }

    // dispose an Inventory
    public function dispose($id, Request $request)
    {
        $inventory = $this->adminDisposeService->dispose($id, $request);

        return response()->json($inventory);
    }

    public function updateInventories(Request $request, $id)
    {
        $this->userUpdateInventoryService->update($request->all(), $id);

        return response()->json(['success' => true]);
    }
}
