<?php

namespace App\Models;

use App\Services\ReturnInventoriesServices;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    //
    protected $fillable = [
        'item_no',
        'description',
        'doc_date',
        'unit_code',
        'quantity',
        'document_status',
        'retention_period',
        'disposal_date',
        'inventory_id',
        'rds_no',
    ];

    protected $casts = [
        'doc_date'      => 'date',
        'disposal_date' => 'date',
    ];
    
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function grds_list() {
        return $this->hasOne(GrdsLists::class);
    }
}
