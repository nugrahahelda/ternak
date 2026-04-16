<?php

namespace App\Filament\Widgets;

use App\Models\Animal;
use App\Models\HealthRecord;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalAyam = Animal::where('type', 'ayam')->sum('quantity');
        $totalEntok = Animal::where('type', 'entok')->sum('quantity');
        $totalIkan = Animal::where('type', 'ikan')->sum('quantity');

        $sickAnimals = HealthRecord::where('type', 'sakit')
            ->whereDate('date', '>=', now()->subDays(30))
            ->distinct('animal_id')
            ->count('animal_id');

        return [
            Stat::make('Total Ayam', $totalAyam.' ekor')
                ->description('Jumlah seluruh ayam')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),
            Stat::make('Total Entok', $totalEntok.' ekor')
                ->description('Jumlah seluruh entok')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            Stat::make('Total Ikan', $totalIkan.' ekor')
                ->description('Jumlah seluruh ikan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Hewan Sakit', $sickAnimals.' hewan')
                ->description('Dalam 30 hari terakhir')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
