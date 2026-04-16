<?php

namespace App\Notifications;

use App\Models\HealthRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VaccineReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public HealthRecord $healthRecord
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pengingat Vaksinasi',
            'body' => "Jadwal vaksinasi untuk {$this->healthRecord->animal->type} milik {$this->healthRecord->animal->farmer->name} besok ({$this->healthRecord->date->format('d/m/Y')})",
            'icon' => 'heroicon-o-shield-check',
            'iconColor' => 'warning',
            'health_record_id' => $this->healthRecord->id,
            'animal_id' => $this->healthRecord->animal_id,
        ];
    }
}
