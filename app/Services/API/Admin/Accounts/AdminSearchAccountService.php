<?php

namespace App\Services\API\Admin\Accounts;

use App\Models\User;

class AdminSearchAccountService
{
    public function findAccount( ? string $search, int $perPage = 6 )
    {
        return User::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })->paginate($perPage);
    }
}
