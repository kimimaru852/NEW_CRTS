<?php

namespace App\Services\API\Manager\Reports;

use Illuminate\Support\Facades\Auth;
use App\Models\ArchiveInventories;

class ManagerCountInventoryService
{
    public function count()
    {
        return ArchiveInventories::whereHas('user', function ($query) {
            $query->where('managerId', Auth::id());
        })->count();
    }
}