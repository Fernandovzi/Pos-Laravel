<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;

class ExportPDFController extends Controller
{
    /**
     * Exportar en formato PDF el comprobante de venta.
     * Si es FACTURA usa un formato dedicado con datos fiscales.
     */
    public function exportPdfComprobanteVenta(Request $request): Response
    {
        $id = Crypt::decrypt($request->id);

        $venta = Venta::with([
            'comprobante',
            'cliente.persona.regimenFiscalCatalogo',
            'cliente.persona.usoCfdiCatalogo',
            'user.empleado',
            'productos.presentacione',
            'pagos',
        ])->findOrFail($id);

        $empresa = Empresa::first();

        $isFactura = str_contains(strtoupper($venta->comprobante->nombre), 'FACTURA');
        $template = $isFactura ? 'pdf.comprobante-factura-venta' : 'pdf.comprobante-venta';

        $pdf = Pdf::loadView($template, [
            'venta' => $venta,
            'empresa' => $empresa,
        ]);

        $nombreArchivo = $isFactura ? 'factura-' . $venta->id : 'ticket-' . $venta->id;

        return $pdf->stream($nombreArchivo . '.pdf');
    }
}
