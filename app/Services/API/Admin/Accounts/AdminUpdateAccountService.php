<?php

namespace App\Services\API\Admin\Accounts;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminUpdateAccountService
{
    public function update(User $user, array $data, ?UploadedFile $signature = null)
    {
        $user->name = $data['name'];
        $user->email = $data['email'];

        unset($data['signature']);
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if ($signature && $signature->isValid()) {
            if (
                $user->signature &&
                Storage::disk('public')->exists($user->signature)
            ) {
                Storage::disk('public')->delete($user->signature);
            }
            $user->signature = $signature->store('signatures', 'public');
        }
        $user->save();

        $user->save();
        return $user;
    }
}
