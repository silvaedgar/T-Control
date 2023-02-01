<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','group_id','description'];

    public function ProductGroup()
    {
        return $this->belongsTo(ProductGroup::class, 'group_id', 'id');
    }

    public function Product()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    // Ambito Local del Scope
    public function scopeLoadCategoryAndGroup($query) {
        return $query->with('ProductGroup')->where('status','Activo');
    }
}
