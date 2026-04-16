<?php

namespace App\Filament\Resources\FeedRecords\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FeedRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('animal_id')
                    ->label('Ternak')
                    ->relationship('animal', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->farmer->name} - {$record->type} ({$record->quantity} ekor)")
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->required()
                    ->native(false)
                    ->maxDate(now()),
                TextInput::make('feed_type')
                    ->label('Jenis Pakan')
                    ->required()
                    ->placeholder('Contoh: Pakan Starter, Pakan Layer, Bekatul'),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->step(0.01),
                Select::make('unit')
                    ->label('Satuan')
                    ->options([
                        'kg' => 'Kilogram (kg)',
                        'gram' => 'Gram (g)',
                        'liter' => 'Liter (L)',
                    ])
                    ->required(),
            ]);
    }
}
