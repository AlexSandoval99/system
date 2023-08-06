<?php

namespace App\Exports;

use App\Models\Proveedor as Proveedor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class NombreExport implements FromCollection
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
}