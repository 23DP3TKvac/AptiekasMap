<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MedicineSet extends Model {
    protected $fillable = ['user_id', 'name', 'description'];

    public function items() {
        return $this->hasMany(MedicineSetItem::class, 'set_id');
    }

    public function medicines() {
        return $this->belongsToMany(Medicine::class, 'medicine_set_items', 'set_id', 'medicine_id');
    }
}
