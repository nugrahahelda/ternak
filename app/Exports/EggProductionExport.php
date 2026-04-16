<?php

namespace App\Exports;

use App\Models\EggProduction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EggProductionExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $startDate = null,
        protected ?string $endDate = null
    ) {}

    public function collection()
    {
        return EggProduction::query()
            ->with(['animal.farmer'])
            ->when($this->startDate, fn ($query) => $query->whereDate('date', '>=', $this->startDate))
            ->when($this->endDate, fn ($query) => $query->whereDate('date', '<=', $this->endDate))
            ->orderBy('date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Peternak',
            'Jenis Ternak',
            'Jumlah Ekor',
            'Jumlah Telur',
            'Catatan',
        ];
    }

    public function map($eggProduction): array
    {
        return [
            $eggProduction->date->format('d/m/Y'),
            $eggProduction->animal->farmer->name,
            ucfirst($eggProduction->animal->type),
            $eggProduction->animal->quantity,
            $eggProduction->quantity,
            $eggProduction->notes ?? '-',
        ];
    }
}
