<?php

namespace App\Exports;

use App\Models\Caja;
use App\Models\Movimiento;
use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class MovimientosCajaExport implements FromArray, WithEvents, ShouldAutoSize
{
    public function __construct(protected Caja $caja) {}

    public function array(): array
    {
        $caja = $this->caja->load('movimientos');

        $numerosComprobante = $caja->movimientos
            ->map(fn(Movimiento $movimiento) => $this->extractNumeroComprobante($movimiento->descripcion))
            ->filter()
            ->unique()
            ->values();

        $ventasPorComprobante = Venta::with('cliente.persona')
            ->whereIn('numero_comprobante', $numerosComprobante)
            ->get()
            ->keyBy('numero_comprobante');

        $rows = $caja->movimientos->map(function (Movimiento $movimiento) use ($ventasPorComprobante) {
            $numeroComprobante = $this->extractNumeroComprobante($movimiento->descripcion);
            $venta = $numeroComprobante ? $ventasPorComprobante->get($numeroComprobante) : null;

            return [
                $movimiento->created_at?->format('d/m/Y H:i') ?? '-',
                $movimiento->tipo?->value ?? '-',
                $movimiento->descripcion,
                $venta?->cliente?->persona?->razon_social ?? 'No aplica',
                $movimiento->metodo_pago?->label() ?? 'No definido',
                (float) $movimiento->monto,
            ];
        })->toArray();

        return array_merge([
            ['Fecha', 'Tipo', 'Descripción', 'Cliente', 'Método de pago', 'Monto'],
        ], $rows);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1, 15);

                $logoPath = public_path('img/maleri.png');
                if (file_exists($logoPath)) {
                    $logo = new Drawing();
                    $logo->setName('Maleri');
                    $logo->setDescription('Logotipo de Maleri');
                    $logo->setPath($logoPath, false);
                    $logo->setHeight(55);
                    $logo->setCoordinates('A1');
                    $logo->setWorksheet($sheet);
                }

                $movimientos = $this->caja->movimientos;
                $totalIngresos = (float) $movimientos
                    ->where('tipo.value', 'VENTA')
                    ->sum('monto');
                $totalEgresos = (float) $movimientos
                    ->where('tipo.value', 'RETIRO')
                    ->sum('monto');
                $saldoInicial = (float) $this->caja->saldo_inicial;
                $saldoFinal = (float) ($this->caja->saldo_final ?? ($saldoInicial + $totalIngresos - $totalEgresos));
                $totalMovimientos = (int) $movimientos->count();
                $lastColumn = 'F';
                $headerRow = 16;
                $lastRow = max($headerRow, $sheet->getHighestRow());

                $sheet->setCellValue('C1', 'Maleri - Cierre de caja');
                $sheet->setCellValue('C2', 'Caja: ' . $this->caja->nombre);
                $sheet->setCellValue('C3', 'Apertura: ' . $this->caja->fecha_apertura . ' ' . $this->caja->hora_apertura);
                $sheet->setCellValue('C4', 'Cierre: ' . ($this->caja->fecha_hora_cierre ? $this->caja->fecha_cierre . ' ' . $this->caja->hora_cierre : 'Caja abierta'));
                $sheet->setCellValue('C5', 'Generado: ' . now()->format('d/m/Y H:i:s'));

                $sheet->mergeCells("C1:{$lastColumn}1");
                $sheet->mergeCells("C2:{$lastColumn}2");
                $sheet->mergeCells("C3:{$lastColumn}3");
                $sheet->mergeCells("C4:{$lastColumn}4");
                $sheet->mergeCells("C5:{$lastColumn}5");

                $sheet->mergeCells('A7:B7');
                $sheet->mergeCells('A8:B9');
                $sheet->mergeCells('C7:D7');
                $sheet->mergeCells('C8:D9');
                $sheet->mergeCells('E7:F7');
                $sheet->mergeCells('E8:F9');
                $sheet->mergeCells('A10:B10');
                $sheet->mergeCells('A11:B12');
                $sheet->mergeCells('C10:D10');
                $sheet->mergeCells('C11:D12');
                $sheet->mergeCells('E10:F10');
                $sheet->mergeCells('E11:F12');

                $sheet->setCellValue('A7', 'Movimientos');
                $sheet->setCellValue('A8', $totalMovimientos);
                $sheet->setCellValue('C7', 'Saldo inicial');
                $sheet->setCellValue('C8', $saldoInicial);
                $sheet->setCellValue('E7', 'Saldo final');
                $sheet->setCellValue('E8', $saldoFinal);
                $sheet->setCellValue('A10', 'Ingresos');
                $sheet->setCellValue('A11', $totalIngresos);
                $sheet->setCellValue('C10', 'Egresos');
                $sheet->setCellValue('C11', $totalEgresos);
                $sheet->setCellValue('E10', 'Estado de caja');
                $sheet->setCellValue('E11', $this->caja->estado == 1 ? 'Aperturada' : 'Cerrada');

                $desglosePagos = $movimientos
                    ->groupBy(fn(Movimiento $movimiento) => $movimiento->metodo_pago?->label() ?? 'No definido')
                    ->map(function ($grupo) {
                        return [
                            'ingreso' => (float) $grupo->where('tipo.value', 'VENTA')->sum('monto'),
                            'egreso' => (float) $grupo->where('tipo.value', 'RETIRO')->sum('monto'),
                        ];
                    });

                $sheet->setCellValue('H7', 'Desglose de pagos');
                $sheet->mergeCells('H7:J7');
                $sheet->setCellValue('H8', 'Método');
                $sheet->setCellValue('I8', 'Ingreso');
                $sheet->setCellValue('J8', 'Egreso');

                $rowMetodo = 9;
                foreach ($desglosePagos as $metodo => $totales) {
                    if ($rowMetodo > 13) {
                        break;
                    }

                    $sheet->setCellValue("H{$rowMetodo}", (string) $metodo);
                    $sheet->setCellValue("I{$rowMetodo}", (float) ($totales['ingreso'] ?? 0));
                    $sheet->setCellValue("J{$rowMetodo}", (float) ($totales['egreso'] ?? 0));
                    $rowMetodo++;
                }

                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9D9D9'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A7:F12')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9D9D9'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A7:F10')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle('A8:F12')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A8:D11')->getFont()->setBold(true);
                $sheet->getStyle('E11')->getFont()->setBold(true);
                $sheet->getStyle('E11')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $this->caja->estado == 1 ? 'C6EFCE' : 'E85757'],
                    ],
                ]);

                $sheet->getStyle('H7:J7')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('H8:J8')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DCE6F1'],
                    ],
                ]);
                $sheet->getStyle('H8:J13')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9D9D9'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A8:F9')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFFFF'],
                    ],
                ]);

                $sheet->getStyle('C8')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getStyle('E8')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getStyle('A11')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getStyle('C11')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getStyle('I9:J13')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getStyle("F" . ($headerRow + 1) . ":F{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $sheet->getStyle('C1:C5')->getFont()->setBold(true);
                $sheet->setAutoFilter("A{$headerRow}:{$lastColumn}{$lastRow}");
                $sheet->freezePane('A' . ($headerRow + 1));
                $sheet->getColumnDimension('H')->setWidth(26);
                $sheet->getColumnDimension('I')->setWidth(14);
                $sheet->getColumnDimension('J')->setWidth(14);
            },
        ];
    }

    private function extractNumeroComprobante(string $descripcion): ?string
    {
        if (! preg_match('/(?:Cancelación de )?venta n°\s*([A-Z0-9]+)/iu', $descripcion, $matches)) {
            return null;
        }

        return $matches[1] ?? null;
    }
}
