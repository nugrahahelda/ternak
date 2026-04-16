<?php

namespace App\Filament\Widgets;

use App\Models\FeedRecord;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class FeedConsumptionChart extends ChartWidget
{
    protected ?string $heading = 'Konsumsi Pakan Per Bulan';

    protected function getData(): array
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $monthlyData = FeedRecord::query()
                ->select(
                    DB::raw("CAST(strftime('%m', date) as INTEGER) as month"),
                    DB::raw("CAST(strftime('%Y', date) as INTEGER) as year"),
                    DB::raw('SUM(CASE WHEN unit = "kg" THEN quantity WHEN unit = "gram" THEN quantity/1000 ELSE 0 END) as total_kg')
                )
                ->where('date', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
        } else {
            $monthlyData = FeedRecord::query()
                ->select(
                    DB::raw('MONTH(date) as month'),
                    DB::raw('YEAR(date) as year'),
                    DB::raw('SUM(CASE WHEN unit = "kg" THEN quantity WHEN unit = "gram" THEN quantity/1000 ELSE 0 END) as total_kg')
                )
                ->where('date', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
        }

        $labels = [];
        $data = [];

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        foreach ($monthlyData as $item) {
            $labels[] = "{$monthNames[$item->month]} {$item->year}";
            $data[] = round($item->total_kg, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Konsumsi (kg)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
