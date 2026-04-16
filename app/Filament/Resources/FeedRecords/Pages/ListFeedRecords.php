<?php

namespace App\Filament\Resources\FeedRecords\Pages;

use App\Filament\Resources\FeedRecords\FeedRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedRecords extends ListRecords
{
    protected static string $resource = FeedRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
