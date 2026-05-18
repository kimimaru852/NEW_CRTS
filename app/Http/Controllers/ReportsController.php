<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ArchiveInventories;
use App\Models\Offices;
use App\Services\InventoriesArchService;
use App\Services\AdminArchInventories;
use App\Services\ManagerArchInventories;
use App\Services\UserArchInventories;

class ReportsController extends Controller
{
    protected $inventoriesArchService;
    protected $adminArchiveInventory;
    protected $managerArchiveInventory;
    protected $userArchiveInventory;

    public function __construct(
        InventoriesArchService $inventoriesArchService,
        AdminArchInventories $adminArchiveInventory,
        ManagerArchInventories $managerArchiveInventory,
        UserArchInventories $userArchiveInventory,
    ) 
    {
        // Inventories constructor
        $this->inventoriesArchService = $inventoriesArchService;
        $this->adminArchiveInventory = $adminArchiveInventory;
        $this->managerArchiveInventory = $managerArchiveInventory;
        $this->userArchiveInventory = $userArchiveInventory;
    }

    //the function where the archive inventory fetches and display in admin reports
    public function adminReports()
    {

        if (request()->ajax()) {
            return $this->adminArchiveInventory->display();
        }
        // This count all the user's table
        // archive inventories table and office table
        $users = User::count() - 1;
        $inventories = ArchiveInventories::count();
        $office = Offices::count();

        return view('admin.reports', compact('users', 'inventories', 'office'));
    }

    // the function where the archive inventory fetches and display in managers reports
    public function managerReports()
    {
        if (request()->ajax()) {
            return $this->managerArchiveInventory->display();
        }

        $totalInv = $this->managerArchiveInventory->count();

        return view('manager.reports', compact('totalInv'));
    }

    public function userReports()
    {
        if(request()->ajax()) {
            return $this->userArchiveInventory->display();
        }

        $userArchiveInventory = $this->inventoriesArchService->getAll();
        $totalArch = $userArchiveInventory->count();
        return view('user.reports', compact('totalArch'));
    }
}
