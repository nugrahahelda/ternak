<?php

namespace App\Exports;

use App\Models\FeedRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FeedReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected ?int $animalId = null,
        protected ?string $startDate = null,
        protected ?string $endDate = null
    ) {}

    public function collection()
    {
        return FeedRecord::query()
            ->with(['animal.farmer'])
            ->when($this->animalId, fn ($query) => $query->where('animal_id', $this->animalId))
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
            'Jenis Pakan',
            'Jumlah',
            'Satuan',
        ];
    }

    public function map($feedRecord): array
    {
        return [
            $feedRecord->date->format('d/m/Y'),
            $feedRecord->animal->farmer->name,
            ucfirst($feedRecord->animal->type),
            $feedRecord->feed_type,
            $feedRecord->quantity,
            strtoupper($feedRecord->unit),
        ];
    }
}
