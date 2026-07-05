<?php

namespace App\Services\API\Admin\GrdsList;

use App\Models\GrdsLists;

class DeleteGRDSList
{
    public function delete($id)
    {
        $record = GrdsLists::findOrFail($id);
        $record->delete();
    }
}
