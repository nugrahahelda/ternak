<?php

namespace App\Filament\Resources\Cages;

use App\Filament\Resources\Cages\Pages\CreateCage;
use App\Filament\Resources\Cages\Pages\EditCage;
use App\Filament\Resources\Cages\Pages\ListCages;
use App\Filament\Resources\Cages\RelationManagers\AnimalsRelationManager;
use App\Filament\Resources\Cages\Schemas\CageForm;
use App\Filament\Resources\Cages\Tables\CagesTable;
use App\Models\Cage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CageResource extends Resource
{
    protected static ?string $model = Cage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Kandang';

    protected static ?string $modelLabel = 'Kandang';

    protected static ?string $pluralModelLabel = 'Kandang';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'peternak', 'petugas']);
    }

    public static function form(Schema $schema): Schema
    {
        return CageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            AnimalsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCages::route('/'),
            'create' => CreateCage::route('/create'),
            'edit' => EditCage::route('/{record}/edit'),
        ];
    }
}
