<?php

namespace App\Http\Controllers;

use App\Exports\VentasExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelController extends Controller
{
    /**
     * Exportar en EXCEL todas las ventas.
     */
    public function exportExcelVentasAll(): BinaryFileResponse
    {
        $filename = 'ventas_' . now()->format('Y_m_d_His') . '.xlsx';

        return Excel::download(
            new VentasExport(),
            $filename,
            null,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }
}
