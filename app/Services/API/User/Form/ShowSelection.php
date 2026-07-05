<?php

namespace App\Services\API\User\Form;

use App\Models\GrdsLists;

class ShowSelection
{
    // This is the service layer for showing the dropdown option
    public function showSelection()
    {
        return GrdsLists::select(
            'id',
            'description',
            'grds_rds_no',
            'retention_period',
            'document_status'
        )->get();
    }
}
