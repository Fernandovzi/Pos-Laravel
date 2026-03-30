<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ResumenInventarioSheet implements FromArray, WithTitle, WithEvents, ShouldAutoSize
{
    public function __construct(
        protected Collection $productos,
        protected Collection $pedidosNoCancelados,
        protected string $logoPath,
    ) {
    }

    public function title(): string
    {
        return 'Resumen de Inventario';
    }

    public function array(): array
    {
        $pendientesPorProducto = $this->pedidosNoCancelados
            ->flatMap(fn ($pedido) => $pedido->productos)
            ->groupBy('id')
            ->map(fn ($lineas) => $lineas->sum(fn ($producto) => (int) ($producto->pivot->cantidad ?? 0)));

        $rows = $this->productos->map(function ($producto) use ($pendientesPorProducto) {
            $existencia = (int) ($producto->inventario?->cantidad ?? 0);
            $pendiente = (int) ($pendientesPorProducto->get($producto->id, 0));

            return [
                $producto->nombre,
                $existencia,
                $pendiente,
                null,
            ];
        })->toArray();

        return array_merge([
            ['Producto', 'Existencia actual', 'Cantidad en pedidos pendientes', 'Stock disponible real'],
        ], $rows);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1, 4);

                if (file_exists($this->logoPath)) {
                    $logo = new Drawing();
                    $logo->setName('Maleri');
                    $logo->setDescription('Logotipo de Maleri');
                    $logo->setPath($this->logoPath, false);
                    $logo->setHeight(55);
                    $logo->setCoordinates('A1');
                    $logo->setWorksheet($sheet);
                }

                $lastRow = max(5, $sheet->getHighestRow());
                $sheet->setCellValue('C1', 'Maleri - Resumen de inventario');
                $sheet->setCellValue('C2', 'Stock real (existencia + pedidos pendientes no cancelados)');
                $sheet->setCellValue('C3', 'Generado: ' . now()->format('d/m/Y H:i'));
                $sheet->mergeCells('C1:D1');
                $sheet->mergeCells('C2:D2');
                $sheet->mergeCells('C3:D3');

                for ($row = 6; $row <= $lastRow; $row++) {
                    $sheet->setCellValue("D{$row}", "=B{$row}+C{$row}");
                }

                $totalRow = $lastRow + 1;
                $sheet->setCellValue("A{$totalRow}", 'TOTAL');
                $sheet->setCellValue("B{$totalRow}", "=SUM(B6:B{$lastRow})");
                $sheet->setCellValue("C{$totalRow}", "=SUM(C6:C{$lastRow})");
                $sheet->setCellValue("D{$totalRow}", "=SUM(D6:D{$lastRow})");

                $sheet->getStyle("A5:D5")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle("A5:D{$totalRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9D9D9'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A{$totalRow}:D{$totalRow}")->getFont()->setBold(true);
                $sheet->getStyle('C1:C3')->getFont()->setBold(true);
                $sheet->setAutoFilter("A5:D{$lastRow}");
                $sheet->freezePane('A6');
            },
        ];
    }
}
