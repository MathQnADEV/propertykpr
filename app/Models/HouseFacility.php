<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseFacility extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'house_id',
        'facility_id'
    ];

    public function house(){
        return $this->belongsTo(House::class, 'house_id');
    }

    public function facility(){
        return $this->belongsTo(Facility::class, 'facility_id');
    }
}
