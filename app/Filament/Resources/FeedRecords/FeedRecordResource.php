<?php

namespace App\Filament\Resources\FeedRecords;

use App\Filament\Resources\FeedRecords\Pages\CreateFeedRecord;
use App\Filament\Resources\FeedRecords\Pages\EditFeedRecord;
use App\Filament\Resources\FeedRecords\Pages\ListFeedRecords;
use App\Filament\Resources\FeedRecords\Schemas\FeedRecordForm;
use App\Filament\Resources\FeedRecords\Tables\FeedRecordsTable;
use App\Models\FeedRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeedRecordResource extends Resource
{
    protected static ?string $model = FeedRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Catatan Pakan';

    protected static ?string $modelLabel = 'Catatan Pakan';

    protected static ?string $pluralModelLabel = 'Catatan Pakan';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'peternak', 'petugas']);
    }

    public static function form(Schema $schema): Schema
    {
        return FeedRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedRecordsTable::configure($table);
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
            'index' => ListFeedRecords::route('/'),
            'create' => CreateFeedRecord::route('/create'),
            'edit' => EditFeedRecord::route('/{record}/edit'),
        ];
    }
}
