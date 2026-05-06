<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = ['name', 'address', 'latitude', 'longitude', 'phone'];

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }
}
