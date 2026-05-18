<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offices extends Model
{
    //
    protected $fillable = ['department'];

    // One-to-Many: Office has many Users
    public function users() 
    {
        return $this->hasMany(User::class);
    }
    
    // One-to-Many: Office has many Inventories
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
