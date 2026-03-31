<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HousePhoto extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'house_id',
        'photo'
    ];
}
