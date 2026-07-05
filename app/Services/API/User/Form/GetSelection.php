<?php

namespace App\Services\API\User\Form;

use App\Models\GrdsLists;

class GetSelection
{
    public function getAllItems(){
        
        return GrdsLists::all();
    }
}