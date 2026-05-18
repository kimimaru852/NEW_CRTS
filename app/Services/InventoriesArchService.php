<?php

namespace App\Services;

use App\Models\ArchiveInventories;
use Illuminate\Support\Facades\Auth;


class InventoriesArchService
{

    /**
     * Get all countries.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function getAllByAdmin()
    {
        return ArchiveInventories::with('items')->paginate(5);
    }
    public function getAll()
    {
        return ArchiveInventories::with('owner', 'items')
            ->where('user_id', Auth::id())
            ->get();
    }
    
    public function ccmArchive() 
    {
        return ArchiveInventories::with('user', 'items')
        ->whereHas('user', function ($query) {
                    $query->where('managerId', Auth::id());
                })
                ->get();
    }
}
