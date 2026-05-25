<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

// Explicit imports for booted() observer
use App\Models\BankApproval;
use App\Models\House;
use App\Models\SystemNotification;
use App\Models\User;

class MortgageRequest extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'customer_id',
        'house_id',
        'payment_type',
        'duration',
        'bank_name',
        'interest',
        'interest_id',
        'dp_total_amount',
        'loan_total_amount',
        'monthly_amount',
        'dp_percentage',
        'status',
        'documents',
        'house_price',
        'loan_interest_total_amount'
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function registeredUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function interestModel()
    {
        return $this->belongsTo(Interest::class, 'interest_id');
    }

    public function house()
    {
        return $this->belongsTo(House::class, 'house_id');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class, 'mortgage_request_id');
    }

    public function bankApproval()
    {
        return $this->hasOne(BankApproval::class, 'mortgage_request_id');
    }

    public function commission()
    {
        return $this->hasOne(Commission::class, 'mortgage_request_id');
    }

    public function commissionRequests()
    {
        return $this->hasMany(CommissionRequest::class, 'mortgage_request_id');
    }

    public function activePendingCommissionRequest()
    {
        return $this->hasOne(CommissionRequest::class, 'mortgage_request_id')
            ->where('status', 'pending');
    }

    protected static function booted(): void
    {
        static::created(function (MortgageRequest $mortgageRequest) {
            // Buat BankApproval — semua tipe menunggu persetujuan master
            BankApproval::create([
                'mortgage_request_id' => $mortgageRequest->id,
                'status'              => 'Waiting for Bank',
            ]);

            // Kirim notifikasi ke semua user dengan role master — bulk insert (1 query)
            $houseName = House::find($mortgageRequest->house_id)?->name ?? ('Properti #' . $mortgageRequest->house_id);
            $isCash    = $mortgageRequest->payment_type === 'cash';
            $title     = $isCash ? 'Pembelian Cash Baru' : 'Pengajuan KPR Baru';
            $desc      = ($isCash ? '[Cash] ' : '[KPR] ') . $houseName;
            $url       = '/admin/mortgage-requests/' . $mortgageRequest->id . '/edit';
            $now       = now();

            $masters = User::role('master')->select('id')->get();
            if ($masters->isNotEmpty()) {
                SystemNotification::insert(
                    $masters->map(fn (User $master) => [
                        'user_id'     => $master->id,
                        'type'        => 'new_mortgage_request',
                        'title'       => $title,
                        'description' => $desc,
                        'url'         => $url,
                        'is_read'     => false,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ])->toArray()
                );
            }
        });
    }

    public function getRemainingLoanAmountAttribute()
    {
        // Use already-loaded relation to avoid N+1 queries.
        // Callers should eager-load: ->with('installments')
        $installments = $this->relationLoaded('installments')
            ? $this->installments
            : $this->installments()->get();

        if ($installments->isEmpty()) {
            return $this->loan_interest_total_amount;
        }

        $totalPaid = $installments->where('is_paid', true)->sum('sub_total_amount');

        return max($this->loan_interest_total_amount - $totalPaid, 0);
    }

    public function getDocumentUrlAttribute()
    {
        return $this->documents
            ? Storage::url($this->documents)
            : null;
    }
}
