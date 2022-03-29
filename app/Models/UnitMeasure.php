<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitMeasure extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','description','symbol'];


    public function ProductUnitMeasure()
    {
        return $this->hasMany(ProductUnitMeasure::class, 'unitmeasure_id', 'id');
    }
}
