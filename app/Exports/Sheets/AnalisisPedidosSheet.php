<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AnalisisPedidosSheet implements FromArray, WithTitle, WithEvents, ShouldAutoSize
{
    public function __construct(
        protected Collection $productos,
        protected Collection $pedidosNoCancelados,
        protected string $logoPath,
    ) {
    }

    public function title(): string
    {
        return 'Análisis de pedidos';
    }

    public function array(): array
    {
        $vendedores = $this->vendedores();
        $cantidades = [];

        foreach ($this->pedidosNoCancelados as $pedido) {
            $vendedor = $this->nombreVendedor($pedido);

            foreach ($pedido->productos as $producto) {
                $cantidades[$producto->id][$vendedor] = ($cantidades[$producto->id][$vendedor] ?? 0)
                    + (int) ($producto->pivot->cantidad ?? 0);
            }
        }

        $rows = $this->productos->map(function ($producto) use ($vendedores, $cantidades) {
            $cantidadesProducto = $cantidades[$producto->id] ?? [];
            $totalEnPedidos = array_sum($cantidadesProducto);
            $row = [
                $producto->codigo,
                $producto->nombre,
                max(0, (int) ($producto->inventario?->cantidad ?? 0) - $totalEnPedidos),
            ];

            foreach ($vendedores as $vendedor) {
                $row[] = (int) ($cantidadesProducto[$vendedor] ?? 0);
            }

            $row[] = $totalEnPedidos;

            return $row;
        })->toArray();

        return array_merge([
            array_merge(['Código', 'Producto', 'Maleri'], $vendedores->all(), ['Total en pedidos']),
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

                $lastColumn = $sheet->getHighestColumn();
                $lastColumnIndex = Coordinate::columnIndexFromString($lastColumn);
                $lastRow = max(5, $sheet->getHighestRow());

                $sheet->setCellValue('C1', 'Maleri - Análisis de pedidos por vendedor');
                $sheet->setCellValue('C2', 'Cantidades asignadas en pedidos vigentes y productos disponibles en Maleri');
                $sheet->setCellValue('C3', 'Generado: ' . now()->format('d/m/Y H:i'));
                $sheet->mergeCells("C1:{$lastColumn}1");
                $sheet->mergeCells("C2:{$lastColumn}2");
                $sheet->mergeCells("C3:{$lastColumn}3");

                $totalRow = $lastRow + 1;
                $sheet->setCellValue("A{$totalRow}", 'TOTAL');
                for ($column = 3; $column <= $lastColumnIndex; $column++) {
                    $letter = Coordinate::stringFromColumnIndex($column);
                    $sheet->setCellValue("{$letter}{$totalRow}", "=SUM({$letter}6:{$letter}{$lastRow})");
                }

                $sheet->getStyle("A5:{$lastColumn}5")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F4E78']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle("A5:{$lastColumn}{$totalRow}")->applyFromArray([
                    'borders' => ['allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D9D9D9'],
                    ]],
                ]);
                $sheet->getStyle("A{$totalRow}:{$lastColumn}{$totalRow}")->getFont()->setBold(true);
                $sheet->getStyle('C1:C3')->getFont()->setBold(true);
                $sheet->setAutoFilter("A5:{$lastColumn}{$lastRow}");
                $sheet->freezePane('C6');
            },
        ];
    }

    private function vendedores(): Collection
    {
        return $this->pedidosNoCancelados
            ->map(fn ($pedido) => $this->nombreVendedor($pedido))
            ->unique()
            ->sort()
            ->values();
    }

    private function nombreVendedor($pedido): string
    {
        return $pedido->proveedore?->persona?->razon_social ?? 'Sin vendedor';
    }
}
