<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateProfile
{
    // FileUpload
    public function update(User $user, array $data, ?UploadedFile $signature = null): void
    {
        unset($data['signature']);
        $user->fill($data);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($signature && $signature->isValid()) {
            if (
                $user->signature &&
                Storage::disk('public')->exists($user->signature)
            ) {
                Storage::disk('public')->delete($user->signature);
            }
            $user->signature = $signature->store('signatures','public');
        }
        $user->save();
    }
    
    // Canvas
    // public function update(User $user, array $data, ?string $signature = null): void
    // {
    //     unset($data['signature']);

    //     $user->fill($data);

    //     if ($user->isDirty('email')) {
    //         $user->email_verified_at = null;
    //     }

    //     if ($signature) {

    //         // delete old
    //         if ($user->signature && Storage::disk('public')->exists($user->signature)) {
    //             Storage::disk('public')->delete($user->signature);
    //         }

    //         // convert base64 to file
    //         $image = str_replace('data:image/png;base64,', '', $signature);
    //         $image = str_replace(' ', '+', $image);

    //         $fileName = 'signatures/' . uniqid() . '.png';

    //         Storage::disk('public')->put($fileName, base64_decode($image));

    //         $user->signature = $fileName;
    //     }

    //     $user->save();
    // }
}
