<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoriesExportAll implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Inventory::with('items', 'office', 'owner')
            ->whereNotNull('manager_approval')
            ->get()
            ->flatMap(function ($inventory) {
                return $inventory->items->map(function ($item) use ($inventory) {
                    return [
                        'inventory_id' => $inventory->id,
                        'office_origin' => $inventory->office_origin,
                        'created_at' => optional($inventory->created_at)->format('Y-m-d'),
                        'prepared_by'        => $inventory->prepared_by,
                        'manager_approval'      => $inventory->manager_approval,


                        'item_no' => $item->item_no,
                        'description' => $item->description,
                        'doc_date' => optional($item->doc_date)->format('Y-m-d'),
                        'quantity' => $item->quantity,
                        'unit_code' => $item->unit_code,
                        'document_status' => $item->document_status,
                        'rds_no' => $item->rds_no,
                        'retention_period' => $item->retention_period,
                        'disposal_date' => optional($item->disposal_date)->format('Y-m-d'),

                        'rack_no' => $inventory->rack_no,
                        'loc_code' => $inventory->loc_code,
                        'received_by' => $inventory->received_by,
                        'received_date' => optional($inventory->received_date)->format('Y-m-d'),
                        'verified_by' => $inventory->verified_by,
                        'verified_date' => optional($inventory->verified_date)->format('Y-m-d'),
                    ];
                });
            });
    }
    public function map($row): array
    {
        return
            [
                $row['inventory_id'],
                $row['office_origin'],
                $row['created_at'],
                $row['prepared_by'],
                $row['manager_approval'],
                $row['item_no'],
                $row['description'],
                $row['doc_date'],
                $row['quantity'],
                $row['unit_code'],
                $row['document_status'],
                $row['rds_no'],
                $row['retention_period'],
                $row['disposal_date'],
                $row['rack_no'],
                $row['loc_code'],
                $row['received_by'],
                $row['received_date'],
                $row['verified_by'],
                $row['verified_date'],
            ];
    }

    public function headings(): array
    {
        return [
            'Box No.',
            'Office Origin',
            'Turn-Over Date',
            'PREPARED/TURN-OVER BY',
            'APPROVED BY(Head)',
            'Item No.',
            'Description',
            'Doc Date',
            'Quantity',
            'Unit Code',
            'Document Status',
            'RDS Series No.',
            'Retention Period',
            'Disposal Date',
            'Rack No.',
            'Location Code',
            'Received By',
            'Received Date',
            'Validated By',
            'Validated Date',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Bold headers 
        $sheet->getStyle('A1:T1')->getFont()->setBold(true);

        // Center all text 
        $sheet->getStyle('A:T')->getAlignment()->setHorizontal('center');

        // Optional: Auto-size columns 
        foreach (range('A', 'T') as $col) {
            $sheet->getColumnDimension($col)
                ->setAutoSize(true);
        }
        return [];
    }
}
