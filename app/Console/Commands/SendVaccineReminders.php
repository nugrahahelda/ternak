<?php

namespace App\Console\Commands;

use App\Models\HealthRecord;
use App\Notifications\VaccineReminderNotification;
use Illuminate\Console\Command;

class SendVaccineReminders extends Command
{
    protected $signature = 'reminders:vaccine';

    protected $description = 'Kirim notifikasi pengingat vaksinasi H-1';

    public function handle(): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $vaccineRecords = HealthRecord::query()
            ->with(['animal.farmer'])
            ->where('type', 'vaksin')
            ->whereDate('date', $tomorrow)
            ->get();

        if ($vaccineRecords->isEmpty()) {
            $this->info('Tidak ada jadwal vaksinasi besok.');

            return self::SUCCESS;
        }

        foreach ($vaccineRecords as $record) {
            $record->animal->farmer->notify(new VaccineReminderNotification($record));
        }

        $this->info("Berhasil mengirim {$vaccineRecords->count()} notifikasi vaksinasi.");

        return self::SUCCESS;
    }
}
