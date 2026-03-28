<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProductosControlSheet implements FromArray, WithTitle, WithEvents, ShouldAutoSize
{
    public function __construct(
        protected string $title,
        protected string $encabezado,
        protected string $logoPath,
        protected array $headings,
        protected array $rows,
        protected ?string $dataValidationColumn = null,
        protected array $dataValidationOptions = [],
    ) {
    }

    public function title(): string
    {
        return $this->title;
    }

    public function array(): array
    {
        return array_merge([$this->headings], $this->rows);
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
                $lastRow = max(5, $sheet->getHighestRow());

                $sheet->setCellValue('C1', 'Maleri - Control operativo');
                $sheet->setCellValue('C2', $this->encabezado);
                $sheet->setCellValue('C3', 'Generado: ' . now()->format('d/m/Y H:i'));

                $sheet->mergeCells("C1:{$lastColumn}1");
                $sheet->mergeCells("C2:{$lastColumn}2");
                $sheet->mergeCells("C3:{$lastColumn}3");

                $sheet->getStyle("A5:{$lastColumn}5")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle("A5:{$lastColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9D9D9'],
                        ],
                    ],
                ]);

                $sheet->getStyle("C1:C3")->getFont()->setBold(true);
                $sheet->setAutoFilter("A5:{$lastColumn}{$lastRow}");
                $sheet->freezePane('A6');

                if ($this->dataValidationColumn && $this->dataValidationOptions !== []) {
                    $formula = '"' . implode(',', $this->dataValidationOptions) . '"';
                    $toRow = max($lastRow, 250);

                    for ($row = 6; $row <= $toRow; $row++) {
                        $validation = $sheet->getCell("{$this->dataValidationColumn}{$row}")->getDataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setErrorStyle(DataValidation::STYLE_STOP);
                        $validation->setAllowBlank(true);
                        $validation->setShowDropDown(true);
                        $validation->setShowInputMessage(true);
                        $validation->setShowErrorMessage(true);
                        $validation->setErrorTitle('Estatus inválido');
                        $validation->setError('Selecciona un estatus permitido.');
                        $validation->setFormula1($formula);
                    }
                }
            },
        ];
    }
}
