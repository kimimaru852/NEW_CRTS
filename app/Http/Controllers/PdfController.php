<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\PrintInventoryService;

class PdfController extends Controller
{
    protected $printInventoryService;
    public function __construct(PrintInventoryService $printInventoryService)
    {
        $this->printInventoryService = $printInventoryService;
    }

    public function print(Request $request, $id)
    {
        return $this->printInventoryService->generatePdf($request, $id);
    }
    public function printArch(Request $request, $id)
    {
        return $this->printInventoryService->generateArchPdf($request, $id);
    }
}
