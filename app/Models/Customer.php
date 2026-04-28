<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'email',
        'phone',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'pekerjaan',
        'penghasilan_bulanan',
        'status_pernikahan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function mortgageRequests()
    {
        return $this->hasMany(MortgageRequest::class);
    }
}
