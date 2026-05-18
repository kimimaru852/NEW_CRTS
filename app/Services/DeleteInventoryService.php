<?php

namespace App\Services;

use App\Models\ArchiveInventories;

class DeleteInventoryService 
{
    public function delete(int $id): bool
    {
        $inventory = ArchiveInventories::findOrFail($id);
        return $inventory->delete();
    }
}