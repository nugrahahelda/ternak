<?php

namespace App\Filament\Pages\Reports;

use App\Exports\FeedReportExport;
use App\Models\Animal;
use App\Models\FeedRecord;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class FeedReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Laporan Pakan';

    protected static ?string $title = 'Laporan Pakan';

    protected string $view = 'filament.pages.reports.feed-report';

    public static function getNavigationGroup(): ?string
    {
        return 'Laporan';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('animal_id')
                    ->label('Hewan Ternak')
                    ->options(Animal::with('farmer')->get()->mapWithKeys(fn ($animal) => [$animal->id => "{$animal->farmer->name} - {$animal->type}"]))
                    ->searchable(),
                DatePicker::make('start_date')
                    ->label('Dari Tanggal'),
                DatePicker::make('end_date')
                    ->label('Sampai Tanggal'),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                FeedRecord::query()
                    ->with(['animal.farmer'])
                    ->when($this->data['animal_id'] ?? null, fn (Builder $query, $animalId) => $query->where('animal_id', $animalId))
                    ->when($this->data['start_date'] ?? null, fn (Builder $query, $date) => $query->whereDate('date', '>=', $date))
                    ->when($this->data['end_date'] ?? null, fn (Builder $query, $date) => $query->whereDate('date', '<=', $date))
            )
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('animal.farmer.name')
                    ->label('Peternak')
                    ->searchable(),
                TextColumn::make('animal.type')
                    ->label('Jenis Ternak')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ayam' => 'warning',
                        'entok' => 'info',
                        'ikan' => 'success',
                    }),
                TextColumn::make('feed_type')
                    ->label('Jenis Pakan')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('unit')
                    ->label('Satuan')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),
            ])
            ->filters([
                SelectFilter::make('animal_id')
                    ->label('Hewan Ternak')
                    ->relationship('animal', 'type')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->farmer->name} - {$record->type}")
                    ->searchable()
                    ->preload(),
                Filter::make('date')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date))
                            ->when($data['until'], fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date));
                    }),
            ])
            ->striped()
            ->defaultSort('date', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return Excel::download(
                        new FeedReportExport(
                            $this->data['animal_id'] ?? null,
                            $this->data['start_date'] ?? null,
                            $this->data['end_date'] ?? null
                        ),
                        'laporan-pakan-'.now()->format('Y-m-d').'.xlsx'
                    );
                }),
        ];
    }
}
