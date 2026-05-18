<?php

namespace App\Http\Controllers;

use App\Exports\InventoriesExport;
use App\Exports\ArcInventoriesExport;
use App\Exports\InventoriesExportAll;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    //
    public function exportExcel($id)
    {
        return Excel::download(new InventoriesExport($id), "TransCo_Active_File-RTO_Inventory List Form - Box No._{$id}.xlsx");
    }
    
    public function exportExcelArch($id)
    {
        return Excel::download(new ArcInventoriesExport($id), "TransCo_Archive_File-RTO_Inventory List Form - Box No._{$id}.xlsx");
    }

    public function exportAllExcel()
    {
        return Excel::download(new InventoriesExportAll, 'TransCo_Summary_Files.xlsx');
    }
}
