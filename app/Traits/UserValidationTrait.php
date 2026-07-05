<?php

namespace App\Traits;

use App\Models\User;

trait UserValidationTrait
{
    protected function userExist(string $name, string $email): bool
    {
        return User::where('email', $email)
            ->orWhere('name', $name)
            ->exists();
    }
}
