<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = ['pharmacy_id', 'medicine_id', 'quantity', 'price', 'status'];

    public function pharmacy() { return $this->belongsTo(Pharmacy::class); }
    public function medicine() { return $this->belongsTo(Medicine::class); }
}
