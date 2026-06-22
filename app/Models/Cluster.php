<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cluster extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'name',
        'developer_id'
    ];

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    public function types()
    {
        return $this->hasMany(Type::class);
    }
}
