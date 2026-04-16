<?php

namespace App\Filament\Resources\EggProductions;

use App\Filament\Resources\EggProductions\Pages\CreateEggProduction;
use App\Filament\Resources\EggProductions\Pages\EditEggProduction;
use App\Filament\Resources\EggProductions\Pages\ListEggProductions;
use App\Filament\Resources\EggProductions\Schemas\EggProductionForm;
use App\Filament\Resources\EggProductions\Tables\EggProductionsTable;
use App\Models\EggProduction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EggProductionResource extends Resource
{
    protected static ?string $model = EggProduction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Produksi Telur';

    protected static ?string $modelLabel = 'Produksi Telur';

    protected static ?string $pluralModelLabel = 'Produksi Telur';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'peternak', 'petugas']);
    }

    public static function form(Schema $schema): Schema
    {
        return EggProductionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EggProductionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEggProductions::route('/'),
            'create' => CreateEggProduction::route('/create'),
            'edit' => EditEggProduction::route('/{record}/edit'),
        ];
    }
}
