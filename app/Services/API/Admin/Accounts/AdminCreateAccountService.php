<?php

namespace App\Services\API\Admin\Accounts;

use App\Models\User;
use App\Traits\UserValidationTrait;
use Illuminate\Support\Facades\Hash;

class AdminCreateAccountService
{
    use UserValidationTrait;
    public function createAccount(array $data): ?User
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
        
        $user->assignRole('manager');
        return $user;
    }
}
