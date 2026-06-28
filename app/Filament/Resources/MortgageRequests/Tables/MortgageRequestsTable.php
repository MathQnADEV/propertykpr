<?php

namespace App\Filament\Resources\MortgageRequests\Tables;

use App\Models\CommissionRequest;
use App\Models\MortgageRequest;
use App\Models\SystemNotification;
use App\Models\User;
use App\Services\DeletionRequestService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MortgageRequestsTable
{
    public static function configure(Table $table): Table
    {
        $isMaster = fn(): bool => auth()->check() && auth()->user()->hasRole('master');
        $isAdmin = fn(): bool => auth()->check() && auth()->user()->hasRole('admin');

        return $table
            ->columns([
                ImageColumn::make('house.thumbnail'),
                TextColumn::make('customer.nama_lengkap')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('house.name'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'Waiting for Bank' => 'Dalam Proses',
                        'Approved'         => 'Disetujui',
                        'Rejected'         => 'Ditolak',
                        default            => $state,
                    })
                    ->color(fn($state) => match ($state) {
                        'Waiting for Bank' => 'warning',
                        'Approved'         => 'success',
                        'Rejected'         => 'danger',
                        default            => 'gray',
                    }),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('status')
                    ->label('Status Pengajuan')
                    ->options([
                        'Waiting for Bank' => 'Dalam Proses',
                        'Approved'         => 'Disetujui',
                        'Rejected'         => 'Ditolak',
                    ]),

                // Filter status komisi — memudahkan cari pengajuan untuk request komisi
                SelectFilter::make('komisi')
                    ->label('Status Komisi')
                    ->options([
                        'eligible'       => 'Bisa Diajukan Komisi',
                        'pending'        => 'Komisi Menunggu Persetujuan',
                        'has_commission' => 'Sudah Ada Komisi',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            // Approved, belum ada komisi & request pending, dan berhak ajukan:
                            // master (lintas listing), pembuat transaksi, atau pemilik listing.
                            'eligible' => $query
                                ->where('status', 'Approved')
                                ->when(! auth()->user()?->hasRole('master'), fn (Builder $q) => $q->where(
                                    fn (Builder $sub) => $sub
                                        ->where('user_id', auth()->id())
                                        ->orWhereHas('house', fn (Builder $h) => $h->where('agent_id', auth()->id()))
                                ))
                                ->whereDoesntHave('commission')
                                ->whereDoesntHave('commissionRequests', fn (Builder $q) => $q->where('status', 'pending')),
                            'pending' => $query
                                ->whereHas('commissionRequests', fn (Builder $q) => $q->where('status', 'pending')),
                            'has_commission' => $query->whereHas('commission'),
                            default => $query,
                        };
                    }),

                // Toggle: hanya listing milik saya
                Filter::make('mine')
                    ->label('Listing Saya')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereHas('house', fn (Builder $q) => $q->where('agent_id', auth()->id()))
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn(MortgageRequest $record) => asset('storage/' . $record->documents))
                    ->openUrlInNewTab(),

                // ── Ajukan Komisi ──────────────────────────────────────────
                // Berhak: master (penjual lintas listing), ATAU pembuat/penjual
                // transaksi, ATAU pemilik listing. Komisi mengikuti yang mengajukan.
                Action::make('request_commission')
                    ->label('Ajukan Komisi')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->form([
                        Textarea::make('notes')
                            ->label('Catatan (opsional)')
                            ->placeholder('Catatan tambahan untuk master...')
                            ->maxLength(1000),
                    ])
                    ->visible(fn(MortgageRequest $record): bool =>
                        auth()->check()
                        && auth()->user()->hasRole(['master', 'admin'])
                        && $record->status === 'Approved'
                        && (
                            auth()->user()->hasRole('master')
                            || $record->user_id === auth()->id()
                            || $record->house?->agent_id === auth()->id()
                        )
                        && ! $record->commission()->exists()
                        && ! $record->commissionRequests()->where('status', 'pending')->exists()
                    )
                    ->action(function (MortgageRequest $record, array $data) {
                        // Cek ulang dalam satu transaksi untuk cegah double-submit
                        $alreadyExists = $record->commissionRequests()
                            ->where('status', 'pending')
                            ->exists();

                        if ($alreadyExists) return;

                        CommissionRequest::create([
                            'mortgage_request_id' => $record->id,
                            'agent_id'            => auth()->id(),
                            'notes'               => $data['notes'] ?? null,
                            'status'              => 'pending',
                        ]);

                        // Notifikasi ke semua master — bulk insert
                        $houseName = $record->house?->name ?? ('Properti #' . $record->house_id);
                        $now       = now();
                        $masters   = User::role('master')->select('id')->get();
                        if ($masters->isNotEmpty()) {
                            SystemNotification::insert(
                                $masters->map(fn($master) => [
                                    'user_id'     => $master->id,
                                    'type'        => 'commission_request',
                                    'title'       => 'Request Komisi Baru',
                                    'description' => '[Request] ' . $houseName . ' dari ' . auth()->user()->name,
                                    'url'         => '/admin/commission-requests',
                                    'is_read'     => false,
                                    'created_at'  => $now,
                                    'updated_at'  => $now,
                                ])->toArray()
                            );
                        }

                        Notification::make()
                            ->title('Request komisi terkirim ke Master')
                            ->success()
                            ->send();
                    }),

                Action::make('direct_delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->authorize($isMaster)
                    ->visible($isMaster)
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->delete()),

                Action::make('request_delete')
                    ->label('Ajukan Penghapusan')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->authorize($isAdmin)
                    ->visible($isAdmin)
                    ->form([
                        Textarea::make('reason')
                            ->label('Alasan Penghapusan')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->action(function ($record, array $data) {
                        DeletionRequestService::requestDeletion($record, $data['reason']);
                        Notification::make()
                            ->title('Permintaan penghapusan telah dikirim ke Master')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize($isMaster)
                        ->visible($isMaster),
                    ForceDeleteBulkAction::make()
                        ->authorize($isMaster)
                        ->visible($isMaster),
                    RestoreBulkAction::make()
                        ->authorize($isMaster)
                        ->visible($isMaster),
                ]),
            ]);
    }
}
