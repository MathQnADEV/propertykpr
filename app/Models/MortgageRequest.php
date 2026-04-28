<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MortgageRequest extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'customer_id',
        'house_id',
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

    protected static function booted(): void
    {
        static::created(function (MortgageRequest $mortgageRequest) {
            BankApproval::create([
                'mortgage_request_id' => $mortgageRequest->id,
                'status'              => 'Waiting for Bank',
            ]);
        });
    }

    public function getRemainingLoanAmountAttribute()
    {
        // check installments if not exist return loan_interest_total_amount
        if ($this->installments()->count() === 0) {
            return $this->loan_interest_total_amount;
        }

        // check installments if exist return remaining loan amount
        $totalPaid = $this->installments()
            ->where('is_paid', true)
            ->sum('sub_total_amount');

        // subtrack
        return max($this->loan_interest_total_amount - $totalPaid, 0);
    }

    public function getDocumentUrlAttribute()
    {
        return $this->documents
            ? Storage::url($this->documents)
            : null;
    }
}
