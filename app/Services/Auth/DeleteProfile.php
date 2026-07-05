<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DeleteProfile
{
    public function delete(User $user, string $password): bool
    {
        if (!Hash::check($password, $user->password)) {
            return false;
        }
        Auth::logout();

        $user->delete();
        session()->invalidate();
        session()->regenerateToken();

        return true;
    }
}
