<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrdsLists extends Model
{
    //
    protected $table = 'gdrslists';

    protected $fillable = [
        'description',
        'grds_rds_no',
        'retention_period',
        'document_status',
    ];

    public function item() {
        return $this->belongsTo(InventoryItem::class);
    }
}
