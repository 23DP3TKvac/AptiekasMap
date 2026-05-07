<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['name', 'active_substance', 'form', 'dose', 'manufacturer', 'description'];

    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'medicine_id');
    }
}
