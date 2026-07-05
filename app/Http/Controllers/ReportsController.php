<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ArchiveInventories;
use App\Models\Offices;
use App\Services\API\Admin\Reports\AdminInventoryArchiveService;
use App\Services\API\Manager\Reports\ManagerInventoryArchiveService;
use App\Services\API\Manager\Reports\ManagerCountInventoryService;
use App\Services\API\User\Reports\UserInventoryArchiveService;

class ReportsController extends Controller
{
    protected $inventoriesArchService;
    protected $adminInventoryArchiveService;
    protected $userInventoryArchiveService;
    protected $managerInventoryArchiveService;
    protected $managerCountInventoryService;

    public function __construct(
        AdminInventoryArchiveService $adminInventoryArchiveService,
        UserInventoryArchiveService $userInventoryArchiveService,
        ManagerInventoryArchiveService $managerInventoryArchiveService,
        ManagerCountInventoryService $managerCountInventoryService,
    ) 
    {
        // Inventories constructor
        $this->adminInventoryArchiveService = $adminInventoryArchiveService;
        $this->userInventoryArchiveService = $userInventoryArchiveService;
        $this->managerInventoryArchiveService = $managerInventoryArchiveService;
        $this->managerCountInventoryService = $managerCountInventoryService;
    }

    //the function where the archive inventory fetches and display in admin reports
    public function adminReports()
    {

        if (request()->ajax()) {
            return $this->adminInventoryArchiveService->display();
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
            return $this->managerInventoryArchiveService->display();
        }

        $totalInv = $this->managerCountInventoryService->count();

        return view('manager.reports', compact('totalInv'));
    }

    public function userReports()
    {
        if(request()->ajax()) {
            return $this->userInventoryArchiveService->display();
        }

        $userInventoryArchiveService = $this->userInventoryArchiveService->getAll();
        $totalArch = $userInventoryArchiveService->count();
        return view('user.reports', compact('totalArch'));
    }
}
