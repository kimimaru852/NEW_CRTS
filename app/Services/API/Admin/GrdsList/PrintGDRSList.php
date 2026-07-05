<?php

namespace App\Services\API\Admin\GrdsList;

use App\Models\GrdsLists;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintGDRSList 
{
    public function printPdf()
    {
        $list = GrdsLists::all();
        $printable = Pdf::loadView('pdf.grds-rds-list', compact('list'));
        $printable->setPaper('A4', 'landscape');
        return $printable->download('grds-rds-list.pdf');
    }
}
