<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    //
    protected $table = 'inventories';

    protected $fillable = [
        'office_origin',
        'prepared_by',
        'list_no',
        'series_no',
        'loc_code',
        'received_by',
        'received_date',
        'manager_approval',
        'verified_by',
        'verified_date',
        'disposal_status',
        'disposed_date',
        'user_id',
        'office_id',
        'rack_no',
    ];

    protected $casts = [
        'received_date' => 'datetime',
        'verified_date' => 'datetime',
        'rack_no' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Many-to-One: Inventory belongs to one user (owner)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Many-to-One: Inventory belongs to one office
    public function office()
    {
        return $this->belongsTo(Offices::class, 'office_id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Many-to-Many: Inventory can be manipulated by many users
    public function manipulators()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    public function items()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
