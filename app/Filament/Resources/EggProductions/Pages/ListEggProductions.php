<?php

namespace App\Filament\Resources\EggProductions\Pages;

use App\Filament\Resources\EggProductions\EggProductionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEggProductions extends ListRecords
{
    protected static string $resource = EggProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
