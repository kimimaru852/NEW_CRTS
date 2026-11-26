<?php

namespace App\Services\API\Manager\Accounts;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\UserValidationTrait;

class ManagerCreateAccountService
{
    use UserValidationTrait;
    public function createAccount(array $data) : ? User
    {
        if ($this->userExist($data['name'], $data['email'])) {
            return null;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'office_id' => $data['office_id'],
        ]);

        $user->assignRole('user');

        return $user;
    }
}