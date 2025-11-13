<?php

namespace App\Exports\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReadingsStyledExport implements FromView
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('excel.readings', $this->data);
    }
}
