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
        $totalPuyuh = Animal::where('type', 'puyuh')->sum('quantity');
        $totalRemajaPullet = Animal::where('type', 'remaja pullet')->sum('quantity');
        $totalAfkir = Animal::where('type', 'afkir')->sum('quantity');

        $sickAnimals = HealthRecord::where('type', 'sakit')
            ->whereDate('date', '>=', now()->subDays(30))
            ->distinct('animal_id')
            ->count('animal_id');

        return [
            Stat::make('Total Puyuh', $totalPuyuh.' ekor')
                ->description('Jumlah seluruh puyuh')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),
            Stat::make('Total Remaja Pullet', $totalRemajaPullet.' ekor')
                ->description('Jumlah seluruh remaja pullet')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            Stat::make('Total Afkir', $totalAfkir.' ekor')
                ->description('Jumlah seluruh afkir')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Hewan Sakit', $sickAnimals.' hewan')
                ->description('Dalam 30 hari terakhir')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}