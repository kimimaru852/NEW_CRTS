<?php

namespace App\Services\API\Admin\Accounts;

use App\Models\User;

class AdminDisplayAccountService
{
    public function display(array $roles = ['manager', 'user'], int $perPage = 6)
    {
        $users = User::whereHas("roles", function ($query) use ($roles) {
            $query->whereIn("name", $roles);
        })->paginate($perPage);

        $roleMapping = [
            'manager' => 'Head',
            'user' => 'User',
        ];

        $users->getCollection()->transform(function ($user) use ($roleMapping) {
            $user->display_roles = $user->getRoleNames()
                ->map(fn($role) => $roleMapping[$role] ?? ucfirst($role)) // fallback
                ->join(', ');

            return $user;
        });

        return $users;
    }
}
