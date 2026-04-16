<?php

namespace App\Filament\Pages\Reports;

use App\Exports\EggProductionExport;
use App\Models\EggProduction;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class EggProductionReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Laporan Produksi Telur';

    protected static ?string $title = 'Laporan Produksi Telur';

    protected string $view = 'filament.pages.reports.egg-production-report';

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
                DatePicker::make('start_date')
                    ->label('Dari Tanggal'),
                DatePicker::make('end_date')
                    ->label('Sampai Tanggal'),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                EggProduction::query()
                    ->with(['animal.farmer'])
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
                TextColumn::make('animal.quantity')
                    ->label('Jumlah Ekor')
                    ->numeric(),
                TextColumn::make('quantity')
                    ->label('Jumlah Telur')
                    ->numeric(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(30)
                    ->default('-'),
            ])
            ->filters([
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
                        new EggProductionExport($this->data['start_date'] ?? null, $this->data['end_date'] ?? null),
                        'laporan-produksi-telur-'.now()->format('Y-m-d').'.xlsx'
                    );
                }),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('danger')
                ->action(function () {
                    $data = EggProduction::query()
                        ->with(['animal.farmer'])
                        ->when($this->data['start_date'] ?? null, fn ($query, $date) => $query->whereDate('date', '>=', $date))
                        ->when($this->data['end_date'] ?? null, fn ($query, $date) => $query->whereDate('date', '<=', $date))
                        ->orderBy('date', 'desc')
                        ->get();

                    $pdf = Pdf::loadView('reports.egg-production-pdf', [
                        'data' => $data,
                        'startDate' => $this->data['start_date'] ?? null,
                        'endDate' => $this->data['end_date'] ?? null,
                    ]);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'laporan-produksi-telur-'.now()->format('Y-m-d').'.pdf');
                }),
        ];
    }
}
