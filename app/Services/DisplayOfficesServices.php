<?php

namespace App\Services;

use App\Models\Offices;

class DisplayOfficesServices 
{
    public function display()
    {
        return Offices::all();
    }
}