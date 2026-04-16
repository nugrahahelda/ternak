<?php

namespace App\Observers;

use App\Models\FeedRecord;
use App\Notifications\FeedStockAlertNotification;
use Illuminate\Support\Facades\DB;

class FeedRecordObserver
{
    public function created(FeedRecord $feedRecord): void
    {
        $this->checkFeedStock($feedRecord);
    }

    public function updated(FeedRecord $feedRecord): void
    {
        $this->checkFeedStock($feedRecord);
    }

    protected function checkFeedStock(FeedRecord $feedRecord): void
    {
        $sevenDaysAgo = now()->subDays(7);
        $driver = DB::connection()->getDriverName();

        $totalFeed = FeedRecord::query()
            ->where('animal_id', $feedRecord->animal_id)
            ->where('date', '>=', $sevenDaysAgo)
            ->get()
            ->sum(function ($record) {
                return $record->unit === 'kg' ? $record->quantity : $record->quantity / 1000;
            });

        $threshold = 100;

        if ($totalFeed > $threshold) {
            $feedRecord->animal->farmer->notify(
                new FeedStockAlertNotification($feedRecord->animal, $totalFeed)
            );
        }
    }
}
