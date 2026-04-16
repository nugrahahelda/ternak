<?php

namespace App\Filament\Resources\Animals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AnimalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('farmer.name')
                    ->label('Peternak')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Jenis Ternak')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ayam' => 'warning',
                        'entok' => 'info',
                        'ikan' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'ayam' => 'Ayam',
                        'entok' => 'Entok',
                        'ikan' => 'Ikan',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->suffix(' ekor')
                    ->sortable(),
                TextColumn::make('date_acquired')
                    ->label('Tanggal Diperoleh')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(50)
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diupdate Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis Ternak')
                    ->options([
                        'ayam' => 'Ayam',
                        'entok' => 'Entok',
                        'ikan' => 'Ikan',
                    ]),
                SelectFilter::make('farmer')
                    ->label('Peternak')
                    ->relationship('farmer', 'name')
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
            ]);
    }
}
