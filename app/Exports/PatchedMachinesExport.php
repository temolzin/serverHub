<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PatchedMachinesExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $machines;

    public function __construct(Collection $machines)
    {
        $this->machines = $machines;
    }

    public function collection(): Collection
    {
        return $this->machines;
    }

    public function headings(): array
    {
        return [
            'Machine',
            'IP',
            'Operating System',
            'Kernel',
            'Latest Patch',
        ];
    }

    public function map($machine): array
    {
        return [
            filled($machine->machine_name) ? $machine->machine_name : 'N/A',
            filled($machine->internal_ip) ? $machine->internal_ip : 'N/A',
            filled($machine->operations_system) ? $machine->operations_system : 'N/A',
            filled($machine->kernel_version) ? $machine->kernel_version : 'N/A',
            filled($machine->latest_security_patch) ? $machine->latest_security_patch : 'N/A',
        ];
    }
}
