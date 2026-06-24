<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class House extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $casts = [
        'is_available' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'about',
        'price',
        'bedroom',
        'bathroom',
        'certificate',
        'electric',
        'land_area',
        'building_area',
        'facilities',
        'is_available',
        'category_id',
        'city_id',
        'agent_id',
        'developer_id',
        'cluster_id',
        'type_id',
        'latitude',
        'longitude',
        'maps_url',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function photos()
    {
        return $this->hasMany(HousePhoto::class);
    }

    public function interest()
    {
        return $this->hasMany(Interest::class);
    }

    public function houseFacilities(){
        return $this->hasMany(HouseFacility::class, 'house_id');
    }

    public function mortgageRequests(){
        return $this->hasMany(MortgageRequest::class);
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
