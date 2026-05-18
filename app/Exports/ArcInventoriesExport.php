<?php

namespace App\Exports;

use App\Models\ArchiveInventories;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ArcInventoriesExport implements FromCollection, WithStyles, WithEvents
{
    protected $inventoryId;

    public function __construct($inventoryId)
    {
        $this->inventoryId = $inventoryId;
    }

    public function collection(): Collection
    {
        return collect();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // title
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells("A6:H8");
                $sheet->getRowDimension(6)->setRowHeight(1.50);
                $sheet->getRowDimension(7)->setRowHeight(2.25);
                $sheet->getRowDimension(8)->setRowHeight(2.25);

                // border top
                $sheet->getStyle("A9:I9")
                    ->getBorders()
                    ->getTop()
                    ->setBorderStyle(Border::BORDER_THIN);

                // border left
                $sheet->getStyle("F9:F11")
                    ->getBorders()
                    ->getLeft()
                    ->setBorderStyle(Border::BORDER_THIN);

                // load the inventory (with relations)
                $inventory = ArchiveInventories::with(['items', 'office', 'owner'])
                    ->find($this->inventoryId);

                $row = 1;

                // Header / Title
                $sheet->mergeCells("E{$row}:I" . ($row + 4));
                $sheet->setCellValue("E{$row}", "RECORDS TURN-OVER / INVENTORY LIST FORM");
                $sheet->getStyle("E{$row}")->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle("E{$row}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                // Logo
                $sheet->mergeCells("A{$row}:C" . ($row + 4));
                $drawing = new Drawing();
                $drawing->setPath(public_path('images/TranscoLogo.png'));
                $drawing->setHeight(80);
                $drawing->setCoordinates("A{$row}");
                $drawing->setOffsetX(10);
                $drawing->setWorksheet($sheet);

                $row += 6;

                $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
                $row += 2;

                // Metadata rows
                $sheet->setCellValue("A{$row}", "OFFICE ORIGIN: " . ($inventory->office_origin ?? ''));
                $sheet->mergeCells("A{$row}:E{$row}");

                $sheet->setCellValue("F{$row}", "PREPARED / TURN-OVER BY: " . ($inventory->prepared_by ?? ''));
                $sheet->mergeCells("F{$row}:I{$row}");
                $row++;

                $sheet->setCellValue("A{$row}", "TURN-OVER DATE: " . optional($inventory->created_at)->format('Y-m-d'));
                $sheet->mergeCells("A{$row}:E{$row}");

                $sheet->setCellValue("F{$row}", "APPROVED BY(Cost Center Head): " . ($inventory->manager_approval ?? ''));
                $sheet->mergeCells("F{$row}:I$row}");
                $row += 2;

                // Table headings
                $headings = [
                    'ITEM NO.',
                    'DESCRIPTION',
                    'DOC DATE',
                    'QUANTITY',
                    'UNIT_CODE',
                    'RDS SERIES NO.',
                    'DOCUMENT STATUS',
                    'RETENTION PERIOD',
                    'DISPOSAL DATE'
                ];

                $col = 'A';
                foreach ($headings as $heading) {
                    $sheet->setCellValue("{$col}{$row}", $heading);
                    $col++;
                }

                $sheet->getStyle("A{$row}:I{$row}")->getFont()->setBold(true);
                $sheet->getStyle("A{$row}:I{$row}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
                $row++;

                // Items
                foreach ($inventory->items as $item) {
                    $sheet->setCellValue("A{$row}", $item->item_no);
                    $sheet->setCellValue("B{$row}", $item->description);
                    $sheet->setCellValue("C{$row}", optional($item->doc_date)->format('Y-m-d') ?? '');
                    $sheet->setCellValue("D{$row}", $item->quantity);
                    $sheet->setCellValue("E{$row}", $item->unit_code);
                    $sheet->setCellValue("F{$row}", $item->rds_no);
                    $sheet->setCellValue("G{$row}", $item->document_status);
                    $sheet->setCellValue("H{$row}", $item->retention_period);
                    $sheet->setCellValue("I{$row}", optional($item->disposal_date)->format('Y-m-d') ?? '');

                    $sheet->getStyle("A{$row}:I{$row}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);
                    $row++;
                }

                $row += 2;
                $sheet->mergeCells("A{$row}:I{$row}");
                $sheet->setCellValue("A{$row}", "to be filed by records personnel");
                $sheet->getStyle("A{$row}")->getFont()->setItalic(true);
                $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $sheet->getStyle("F" . ($row - 2) . ":F" . ($row - 1))->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $row++;

                // Footer
                $sheet->setCellValue("A{$row}", "BOX NO.: " . ($inventory->id ?? ''));
                $sheet->mergeCells("A{$row}:B{$row}");

                $sheet->setCellValue("D{$row}", "RACK NO.: " . ($inventory->rack_no ?? ''));
                $sheet->mergeCells("D{$row}");

                $sheet->setCellValue("F{$row}", "RECEIVED BY: " . ($inventory->received_by ?? ''));
                $sheet->mergeCells("F{$row}:G{$row}");
                $sheet->getStyle("F{$row}")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->setCellValue("I{$row}", "DATE: " . (
                    $inventory->received_date
                    ? \Carbon\Carbon::parse($inventory->received_date)->format('Y-m-d')
                    : ''
                ));
                $row++;

                $sheet->setCellValue("A{$row}", "LOCATION CODE: " . ($inventory->loc_code ?? ''));
                $sheet->mergeCells("A{$row}:C{$row}");

                $sheet->setCellValue("F{$row}", "VALIDATED BY: " . ($inventory->verified_by ?? ''));
                $sheet->mergeCells("F{$row}:G{$row}");
                $sheet->getStyle("F{$row}")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $sheet->setCellValue("I{$row}", "DATE: " . (
                    $inventory->approved_date
                    ? \Carbon\Carbon::parse($inventory->verified_date)->format('Y-m-d')
                    : ''
                ));

                // ✅ Apply outline border AFTER everything is written
                $lastRow = $sheet->getHighestRow();
                $range = "A1:I{$lastRow}"; // fixed A-H range
                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Center & wrap text
                $sheet->getStyle("A1:I{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                // Auto-size columns
                foreach (range('A', 'H') as $c) {
                    $sheet->getColumnDimension($c)->setAutoSize(true);
                }
            },
        ];
    }
}
