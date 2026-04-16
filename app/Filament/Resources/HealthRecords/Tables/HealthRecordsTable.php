<?php

namespace App\Filament\Resources\HealthRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HealthRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('animal.farmer.name')
                    ->label('Peternak')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('animal.type')
                    ->label('Jenis Ternak')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ayam' => 'warning',
                        'entok' => 'info',
                        'ikan' => 'success',
                    }),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vaksin' => 'success',
                        'sakit' => 'danger',
                        'pengobatan' => 'warning',
                    }),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis')
                    ->options([
                        'vaksin' => 'Vaksin',
                        'sakit' => 'Sakit',
                        'pengobatan' => 'Pengobatan',
                    ]),
                Filter::make('date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        \Filament\Forms\Components\DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date))
                            ->when($data['until'], fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date));
                    }),
                SelectFilter::make('animal_id')
                    ->label('Hewan Ternak')
                    ->relationship('animal', 'type')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->farmer->name} - {$record->type}")
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->defaultSort('date', 'desc');
    }
}
