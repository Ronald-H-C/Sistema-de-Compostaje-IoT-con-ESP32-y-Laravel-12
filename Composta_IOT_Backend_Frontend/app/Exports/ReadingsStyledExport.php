<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReadingsStyledExport implements FromArray, WithStyles, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Reporte de Lecturas';
    }

    public function array(): array
    {
        $rows = [];

        // Título del reporte
        $rows[] = ['📊 Reporte de Compostaje - ' . strtoupper($this->data['range'])];
        $rows[] = [];

        // Promedios generales
        $rows[] = ['Promedios Generales'];
        $rows[] = ['Parámetro', 'Valor Promedio'];
        $rows[] = ['Temperatura Aire (°C)', $this->data['promedioTempAire']];
        $rows[] = ['Humedad Aire (%)', $this->data['promedioHumedad']];
        $rows[] = ['Nivel MQ-135', $this->data['promedioGases']];
        $rows[] = ['Temperatura Suelo (°C)', $this->data['promedioTempSuelo']];
        $rows[] = ['Humedad Suelo (%)', $this->data['promedioHumSuelo']];
        $rows[] = [];

        // Análisis
        $rows[] = ['Análisis'];
        foreach ($this->data['analisis'] as $linea) {
            $rows[] = [$linea];
        }
        $rows[] = [];

        // Momentos críticos
        $rows[] = ['Momentos Críticos'];
        $rows[] = ['Tipo', 'Inicio', 'Fin'];

        foreach ($this->data['momentosCriticos']['temperatura'] as $m) {
            $rows[] = ['Temperatura Alta', $m['inicio'], $m['fin']];
        }

        foreach ($this->data['momentosCriticos']['gases'] as $m) {
            $rows[] = ['Gases Altos', $m['inicio'], $m['fin']];
        }

        $rows[] = [];

        // Lecturas recientes
        $rows[] = ['Lecturas Recientes'];
        $rows[] = ['Fecha', 'Hora', 'Temp Aire', 'Humedad', 'MQ-135', 'Temp Suelo', 'Humedad Suelo'];

        foreach ($this->data['datos'] as $d) {
            $rows[] = [
                $d->date,
                $d->time,
                $d->temperature,
                $d->humidity,
                $d->mq135,
                $d->ds18b20_temp,
                $d->soil_moisture,
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // Encabezados en negrita
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3:A11')->getFont()->setBold(true);
        $sheet->getStyle('A13')->getFont()->setBold(true);
        $sheet->getStyle('A16')->getFont()->setBold(true);
        $sheet->getStyle('A20')->getFont()->setBold(true);
        $sheet->getStyle('A26')->getFont()->setBold(true);

        // Ajustar anchos de columna
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Encabezado de tabla de lecturas
        $sheet->getStyle('A26:G26')->getFont()->setBold(true);
        $sheet->getStyle('A26:G26')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('CCE5FF');

        // Bordes generales
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();
        $sheet->getStyle("A1:{$highestCol}{$highestRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [];
    }
}
