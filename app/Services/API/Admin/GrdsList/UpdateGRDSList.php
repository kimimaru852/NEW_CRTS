<?php

namespace App\Services\API\Admin\GrdsList;

use App\Models\GrdsLists;

class UpdateGRDSList
{
    public function update(array $data, $id)
    {
        $record = GrdsLists::findOrFail($id);
        $documentStatus = $data['document_status'] ?? null;
        $retentionPeriod = $documentStatus === 'Permanent' ? null : ($data['retention_period'] ?? null);

        $record->update([
            'description'      => $data['description'],
            'grds_rds_no'      => $data['grds_rds_no'],
            'document_status'  => $documentStatus,
            'retention_period' => $retentionPeriod,
        ]);

        return $record;
    }
}
