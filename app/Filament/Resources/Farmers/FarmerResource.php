<?php

namespace App\Filament\Resources\Farmers;

use App\Filament\Resources\Farmers\Pages\CreateFarmer;
use App\Filament\Resources\Farmers\Pages\EditFarmer;
use App\Filament\Resources\Farmers\Pages\ListFarmers;
use App\Filament\Resources\Farmers\Schemas\FarmerForm;
use App\Filament\Resources\Farmers\Tables\FarmersTable;
use App\Models\Farmer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FarmerResource extends Resource
{
    protected static ?string $model = Farmer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Peternak';

    protected static ?string $modelLabel = 'Peternak';

    protected static ?string $pluralModelLabel = 'Peternak';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'petugas']);
    }

    public static function form(Schema $schema): Schema
    {
        return FarmerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FarmersTable::configure($table);
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
            'index' => ListFarmers::route('/'),
            'create' => CreateFarmer::route('/create'),
            'edit' => EditFarmer::route('/{record}/edit'),
        ];
    }
}
