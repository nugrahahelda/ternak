<?php

namespace App\Filament\Resources\EggProductions\Pages;

use App\Filament\Resources\EggProductions\EggProductionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEggProduction extends EditRecord
{
    protected static string $resource = EggProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
