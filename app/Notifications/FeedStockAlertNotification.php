<?php

namespace App\Notifications;

use App\Models\Animal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FeedStockAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Animal $animal,
        public float $totalFeed
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Peringatan Stok Pakan',
            'body' => "Konsumsi pakan {$this->animal->type} dalam 7 hari terakhir mencapai {$this->totalFeed} kg. Perlu pengecekan stok.",
            'icon' => 'heroicon-o-exclamation-triangle',
            'iconColor' => 'danger',
            'animal_id' => $this->animal->id,
            'total_feed' => $this->totalFeed,
        ];
    }
}
