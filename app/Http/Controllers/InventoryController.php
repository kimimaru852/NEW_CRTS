<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminApprovalService;
use App\Services\AdminInventoriesService;
use App\Services\AdminRecievingService;
use App\Services\CheckUpdateInventoryService;
use App\Services\CreateInventoryService;
use App\Services\DeleteInventoryService;
use App\Services\DisposeService;
use App\Services\ManagerApprovalService;
use App\Services\ManagerInventoriesService;
use App\Services\TempToDelInventoriesService;
use App\Services\UserInventoriesService;
use App\Services\ManagerTempToDelInventoriesService;
use App\Services\UserUpdateInventories;
use App\Services\ReturnInventoriesServices;

class InventoryController extends Controller
{

    protected $tempToDelInventoriesService;
    protected $adminInventoriesService;
    protected $managerInventoriesService;
    protected $userInventoriesService;
    protected $createInventoryService;
    protected $checkUpdateInventoryService;
    protected $deleteInventoryService;
    protected $adminRecievingService;
    protected $managerApprovalService;
    protected $adminApprovalService;
    protected $disposeService;
    protected $managerTempToDelInventories;
    protected $userUpdateInventories;
    protected $returnInventoriesService;

    public function __construct(
        TempToDelInventoriesService $tempToDelInventoriesService,
        AdminInventoriesService $adminInventoriesService,
        ManagerInventoriesService $managerInventoriesService,
        UserInventoriesService $userInventoriesService,
        CreateInventoryService $createInventoryService,
        CheckUpdateInventoryService $checkUpdateInventoryService,
        DeleteInventoryService $deleteInventoryService,
        AdminRecievingService $adminRecievingService,
        ManagerApprovalService $managerApprovalService,
        AdminApprovalService $adminApprovalService,
        DisposeService $disposeService,
        ManagerTempToDelInventoriesService $managerTempToDelInventories,
        UserUpdateInventories $userUpdateInventories,
        ReturnInventoriesServices $returnInventoriesServices,
    ) {
        $this->tempToDelInventoriesService = $tempToDelInventoriesService;
        $this->adminInventoriesService = $adminInventoriesService;
        $this->managerInventoriesService = $managerInventoriesService;
        $this->userInventoriesService = $userInventoriesService;
        $this->createInventoryService = $createInventoryService;
        $this->deleteInventoryService = $deleteInventoryService;
        $this->managerApprovalService = $managerApprovalService;
        $this->adminRecievingService = $adminRecievingService;
        $this->adminApprovalService = $adminApprovalService;
        $this->checkUpdateInventoryService = $checkUpdateInventoryService;
        $this->disposeService = $disposeService;
        $this->managerTempToDelInventories = $managerTempToDelInventories;
        $this->userUpdateInventories = $userUpdateInventories;
        $this->returnInventoriesService = $returnInventoriesServices;
    }

    public function view()
    {
        return view('user.form');
    }
    public function displayRegister()
    {
        return view('manager.register');
    }

    public function create(Request $request)
    {
        return $this->createInventoryService->createInventory($request);
    }

    public function update(Request $request, int $id, CheckUpdateInventoryService $checkUpdateInventoryService)
    {
        $validated = $request->validate([
            'rack_no' => 'required|integer|min:1',
            'loc_code' => 'required|string|max:50',
        ]);

        $checkUpdateInventoryService->checkUpdate($id, $validated);

        return response()
            ->json(['message' => 'Inventory updated successfully.']);
    }

    public function displayIndexUser()
    {
        if (request()->ajax()) {
            return $this->userInventoriesService->display();
        }

        $inventories = $this->userInventoriesService->count();
        //$totalInv = $inventories->count();

        return view('user.index', compact('inventories'));
    }

    // index display of admin
    public function adminDisplay(Request $request)
    {
        $data = $this->adminInventoriesService->display($request);
        if ($data) return $data;

        return view('admin.index');
    }

    // index display of manager
    public function managerDisplay(Request $request)
    {
        $data = $this->managerInventoriesService->display($request);
        if ($data) return $data;

        return view('manager.index');
    }

    // manager approval
    public function approve(Request $request)
    {
        $this->managerApprovalService->approve($request);

        return redirect()
            ->route('manager.index')
            ->with('success', 'Inventory approved successfully.');
    }

    // RTO recieve by admin
    public function adminRecieve(Request $request)
    {
        $this->adminRecievingService->recieve($request);

        return redirect()
            ->route('admin.index')
            ->with('success', 'Inventory recieved successfully.');
    }

    // RTO approve by admin
    public function adminApprove(Request $request)
    {
        try {
            $this->adminApprovalService->approve($request);

            return redirect()
                ->route('admin.index')
                ->with('success', 'Inventory Verified successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.index')
                ->with('error', $e->getMessage());
        }
    }

    // transfer RTO to archives
    public function destroy($inventoryId)
    {
        $this->tempToDelInventoriesService->toArchiveInventoryAndDelete($inventoryId);

        return redirect()
            ->back()
            ->with('success', 'Inventory archived successfully.');
    }

    public function returnInventory($inventoryId)
    {
        $this->returnInventoriesService->returnInventory($inventoryId);

        return redirect()
            ->back()
            ->with('success', 'Inventory has been marked as returned.');
    }
    public function managerDestroy($inventoryId)
    {
        $this->managerTempToDelInventories->toArchiveInventoryAndDelete($inventoryId);

        return redirect()
            ->back()
            ->with('success', 'Inventory archived successfully.');
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
        $inventory = $this->disposeService->disposal($id, $request);

        return response()->json($inventory);
    }

    public function updateInventories(Request $request, $id)
    {
        $this->userUpdateInventories->updateInventories($request->all(), $id);

        return response()->json(['success' => true]);
    }
}
