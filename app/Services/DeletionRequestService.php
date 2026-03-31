<?php

namespace App\Services;

use App\Models\DeletionRequest;
use Illuminate\Support\Facades\Auth;

class DeletionRequestService
{
    private static array $labels = [
        \App\Models\House::class           => 'Rumah',
        \App\Models\Category::class        => 'Kategori',
        \App\Models\City::class            => 'Kota',
        \App\Models\Bank::class            => 'Bank',
        \App\Models\Facility::class        => 'Fasilitas',
        \App\Models\Interest::class        => 'Bunga',
        \App\Models\HousePhoto::class      => 'Foto Rumah',
        \App\Models\MortgageRequest::class => 'Permohonan KPR',
        \App\Models\Installment::class     => 'Cicilan',
    ];

    public static function requestDeletion($record, string $reason = ''): DeletionRequest
    {
        $user = Auth::user();
        $label = self::$labels[$record::class] ?? class_basename($record::class);
        $name = $record->name ?? $record->email ?? "#{$record->id}";

        $request = DeletionRequest::create([
            'requester_id' => $user->id,
            'model_type'   => $record::class,
            'model_id'     => $record->id,
            'model_name'   => "{$label}: {$name}",
            'reason'       => $reason,
            'status'       => 'pending',
        ]);

        NotificationService::notify(
            'delete_request',
            "Permintaan Hapus: {$label} - {$name}",
            "Admin {$user->name} meminta menghapus {$label} \"{$name}\"\nAlasan: {$reason}\nSilakan review.",
            "/admin/deletion-requests/{$request->id}",
        );

        return $request;
    }

    public static function approve(DeletionRequest $request, string $note = ''): bool
    {
        $record = $request->getModelRecord();
        if (! $record) return false;

        $record = $request->getModelRecord();
        if (! $record) return false;

        NotificationService::$skipLogging = true;  // ← skip
        $record->delete();                          // ←  trigger deleted event
        NotificationService::$skipLogging = false;  // ← back to skip



        $request->update([
            'status'      => 'approved',
            'reviewer_id' => Auth::user()->id,
            'review_note' => $note,
            'reviewed_at' => now(),
        ]);

        NotificationService::notify(
            'delete_approved',
            "Hapus Disetujui: {$request->model_name}",
            "Master " . Auth::user()->name . " menyetujui penghapusan {$request->model_name}.",
        );

        return true;
    }

    public static function reject(DeletionRequest $request, string $note = ''): bool
    {
        $request->update([
            'status'      => 'rejected',
            'reviewer_id' => Auth::user()->id,
            'review_note' => $note,
            'reviewed_at' => now(),
        ]);

        NotificationService::notify(
            'delete_rejected',
            "Hapus Ditolak: {$request->model_name}",
            "Master " . Auth::user()->name . " menolak penghapusan {$request->model_name}.",
        );



        return true;
    }
}
