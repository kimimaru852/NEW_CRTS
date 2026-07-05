<?php

namespace App\Services\API\User\Inventory;

use Illuminate\Support\Facades\Auth;
use App\Models\Inventory;

class UserCountInventoryService
{
    public function count()
    {
        return Inventory::whereHas('owner', function ($query) {
            $query->where('user_id', Auth::id());
        })->count();
    }
}