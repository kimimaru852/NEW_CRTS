<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveInventories extends Model
{
    //
    protected $fillable = [
        'office_origin',
        'prepared_by',
        'list_no',
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
        'doc_date'      => 'date',
        'disposal_date' => 'date',
        'disposed_date' => 'date',
        'received_date' => 'datetime',
        'verified_date' => 'datetime',
    ];

    // Many-to-One: Inventory belongs to one user (owner)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function office()
    {
        return $this->belongsTo(Offices::class, 'office_id');
    }
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    public function items() 
    {
        return $this->hasMany(ArchiveInventoryItems::class);
    }
}
