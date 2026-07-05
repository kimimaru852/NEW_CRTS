<?php
namespace App\Services\API\Admin\Accounts;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminDeleteAccountService
{
    public function destroy(User $user, string $password): bool
    {
        if (!Hash::check($password, Auth::user()->password)) {
            return false;
        }

        return $user->delete();
    }
}