<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class GenericExport implements FromCollection
{
    protected $modelClass;

    public function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function collection()
    {
        return $this->modelClass::all();
    }
}
