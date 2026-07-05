<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\PrintInventoryService;
use App\Services\API\Pdf\PdfActiveService;
use App\Services\API\Pdf\PdfArchiveService;

class PdfController extends Controller
{
    protected $printInventoryService;
    protected $pdfActiveService;
    protected $pdfArchiveService;

    public function __construct(PdfArchiveService $pdfArchiveService, PdfActiveService $pdfActiveService)
    {
        $this->pdfArchiveService = $pdfArchiveService;
        $this->pdfActiveService = $pdfActiveService;
    }

    public function print(Request $request, $id)
    {
        return $this->pdfActiveService->generate($request, $id);
    }
    public function printArch(Request $request, $id)
    {
        return $this->pdfArchiveService->generate($request, $id);
    }
}
