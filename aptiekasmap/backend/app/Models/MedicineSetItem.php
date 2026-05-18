<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MedicineSetItem extends Model {
    protected $fillable = ['set_id', 'medicine_id'];

    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }
}
