<?php

namespace App\Filament\Resources\Animals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AnimalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('farmer_id')
                    ->label('Peternak')
                    ->relationship('farmer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->label('Jenis Ternak')
                    ->options([
                        'ayam' => 'Ayam',
                        'entok' => 'Entok',
                        'ikan' => 'Ikan',
                    ])
                    ->required(),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->suffix('ekor'),
                DatePicker::make('date_acquired')
                    ->label('Tanggal Diperoleh')
                    ->required()
                    ->native(false)
                    ->maxDate(now()),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
