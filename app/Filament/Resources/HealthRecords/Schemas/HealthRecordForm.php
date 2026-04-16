<?php

namespace App\Filament\Resources\HealthRecords\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HealthRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Rekam Kesehatan')
                    ->schema([
                        Select::make('animal_id')
                            ->label('Hewan Ternak')
                            ->relationship('animal', 'type')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->farmer->name} - {$record->type} ({$record->quantity} ekor)")
                            ->searchable()
                            ->preload()
                            ->required(),
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->default(now()),
                        Select::make('type')
                            ->label('Jenis')
                            ->options([
                                'vaksin' => 'Vaksin',
                                'sakit' => 'Sakit',
                                'pengobatan' => 'Pengobatan',
                            ])
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(4),
                    ])
                    ->columns(2),
            ]);
    }
}
