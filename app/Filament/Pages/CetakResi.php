<?php

namespace App\Filament\Pages;

use App\Models\Order;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Actions\Action;

class CetakResi extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPrinter;

    protected static ?string $navigationLabel = 'Cetak Resi';

    protected static \UnitEnum|string|null $navigationGroup = 'Lainnya';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Cetak Resi';

    protected string $view = 'filament.pages.cetak-resi';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['items.product', 'user'])
                    ->where('status', 'shipped')
                    ->whereNotNull('tracking_number')
                    ->where('tracking_number', '!=', '')
                    ->latest()
            )
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('shipping_name')
                    ->label('Penerima')
                    ->searchable(),

                TextColumn::make('courier')
                    ->label('Kurir')
                    ->formatStateUsing(fn ($state, $record) =>
                        strtoupper($state ?? '-') . ($record->courier_service ? ' · ' . $record->courier_service : '')
                    ),

                TextColumn::make('tracking_number')
                    ->label('No. Resi')
                    ->placeholder('Belum tersedia')
                    ->copyable()
                    ->copyMessage('No. resi disalin!'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'shipped'   => 'info',
                        'delivered' => 'success',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'shipped'   => 'Dikirim',
                        'delivered' => 'Selesai',
                        default     => $state,
                    }),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y'),
            ])
            ->actions([
                Action::make('cetak')
                    ->label('Cetak Resi')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->color('primary')
                    ->url(fn (Order $record) => route('admin.resi.download', $record))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->emptyStateHeading('Belum ada resi tersedia')
            ->emptyStateDescription('Pesanan yang sudah memiliki nomor resi akan muncul di sini.')
            ->emptyStateIcon(Heroicon::OutlinedTruck);
    }
}
