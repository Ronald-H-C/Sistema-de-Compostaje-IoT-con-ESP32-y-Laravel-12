<?php

namespace App\Exports\Exports;

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
                'Fecha' => \Carbon\Carbon::parse($sale->fecha)->format('d/m/Y'),
                'Cliente' => $sale->cliente,
                'Total Venta' => $sale->total_venta,
                'Detalles de Productos' => str_replace("\n", "\n", $sale->detalles_productos),
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
            ->getStartColor()->setARGB('eaeaea');
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(60);
        $sheet->getStyle('A:D')->getAlignment()->setWrapText(true);
        return [];
    }
}
