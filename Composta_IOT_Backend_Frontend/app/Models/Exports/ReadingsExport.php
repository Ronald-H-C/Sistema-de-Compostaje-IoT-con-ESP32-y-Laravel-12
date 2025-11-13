<?php

namespace App\Exports;

use App\Models\Reading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReadingsExport implements FromCollection
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Temp. DHT (°C)',
            'Humedad (%)',
            'MQ-135',
            'Calidad del Aire',
            'Temp. DS18B20 (°C)',
            'Humedad Suelo',
            'Fecha',
            'Hora'
        ];
    }
}
