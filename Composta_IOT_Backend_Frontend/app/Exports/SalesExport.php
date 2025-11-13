<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithStyles
{
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function collection()
    {
        return collect($this->rows)->map(function ($sale) {
            return [
                'FECHA'                => \Carbon\Carbon::parse($sale->fecha)->format('d/m/Y'),
                'CLIENTE'              => $sale->cliente,
                'TOTAL VENTA'          => 'Bs ' . number_format((float)$sale->total_venta, 2),
                'DETALLES DE PRODUCTOS' => str_replace('\n', "\n", $sale->detalles_productos),
            ];
        });
    }

    public function headings(): array
    {
        return ['FECHA', 'CLIENTE', 'TOTAL VENTA', 'DETALLES DE PRODUCTOS'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('E0E0E0');

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(60);

        $sheet->getStyle('A:D')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:D')->getAlignment()->setVertical('top');

        $sheet->getStyle('A1:D' . $sheet->getHighestRow())
            ->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [];
    }
}
