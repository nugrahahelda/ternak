<?php

namespace App\Filament\Resources\FeedRecords\Pages;

use App\Filament\Resources\FeedRecords\FeedRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedRecord extends EditRecord
{
    protected static string $resource = FeedRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
