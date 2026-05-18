<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveInventoryItems extends Model
{
    protected $fillable = [
        'item_no',
        'description',
        'doc_date',
        'unit_code',
        'quantity',
        'document_status',
        'retention_period',
        'disposal_date',
        'archive_inventories_id',
        'rds_no',
    ];

    protected $casts = [
        'disposal_date' => 'date',
    ];



    public function archiveInventory()
    {
        return $this->belongsTo(ArchiveInventories::class);
    }
}
