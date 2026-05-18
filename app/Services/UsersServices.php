<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersServices
{
    public function registerManager(array $data): ?User
    {
        if ($this->userExists($data['name'], $data['email'])) {
            return null;
        }

        $manager = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'office_id' => $data['office_id'],
        ]);

        $manager->assignRole('manager');

        return $manager;
    }

    public function registerUser(array $data): ?User
    {
        if ($this->userExists($data['name'], $data['email'])) {
            return null;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'managerId' => Auth::id(),
            'office_id' => Auth::user()->office_id,
        ]);

        $user->assignRole('user');

        return $user;
    }

    public function updateProfile(User $user, array $data): void
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    public function deleteAccount(User $user, string $password): bool
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

    public function usersRoles(array $roles = ['manager', 'user'], int $perPage = 6)
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

    public function searchUsers(?string $search, int $perPage = 6)
    {
        return User::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })->paginate($perPage);
    }

    public function updateUser(User $user, array $data)
    {
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return $user;
    }

    public function deleteUser(User $user, string $password): bool
    {
        if (!Hash::check($password, Auth::user()->password)) {
            return false;
        }

        return $user->delete();
    }

    protected function userExists(string $name, string $email): bool
    {
        return User::where('email', $email)
            ->orWhere('name', $name)
            ->exists();
    }
}
