<?php

namespace App\Services\API\Admin\CostCenter;

use App\Models\Offices;

class AdminDisplayCostCenterService
{
    public function display()
    {
        return Offices::all();
    }
}