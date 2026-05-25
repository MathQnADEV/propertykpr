<?php

namespace App\Filament\Resources\BankApprovals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Support\Facades\Storage;

class BankApprovalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('mortgageRequest.customer.nama_lengkap')
                    ->label('Nasabah'),

                TextEntry::make('mortgageRequest.house.name')
                    ->label('Rumah'),

                TextEntry::make('mortgageRequest.bank_name')
                    ->label('Bank'),

                TextEntry::make('mortgageRequest.loan_total_amount')
                    ->label('Jumlah Pinjaman')
                    ->money('IDR'),

                TextEntry::make('mortgageRequest.duration')
                    ->label('Durasi (Bulan)'),

                TextEntry::make('status')
                    ->label('Status Bank Approval')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'Waiting for Bank' => 'Proses Bank',
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

                TextEntry::make('reviewer.name')
                    ->label('Direview oleh')
                    ->placeholder('-'),

                TextEntry::make('reviewed_at')
                    ->label('Tanggal Review')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),

                TextEntry::make('review_note')
                    ->label('Catatan Review')
                    ->placeholder('-')
                    ->columnSpanFull(),

            MediaAction::make('File Data Nasabah')
                ->media(fn($record) => $record->mortgageRequest?->document_url)
                ->mediaType(MediaAction::TYPE_PDF),

                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i'),

                TextEntry::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i'),
            ]);
    }
}
