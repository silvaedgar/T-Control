<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'group_id', 'description'];

    /* --- */
    public function productGroup()
    {
        return $this->belongsTo(ProductGroup::class, 'group_id', 'id');
    }

    public function product()
    {
        return $this->hasMany(product::class, 'category_id', 'id');
    }

    // Ambito Local del Scope
    public function scopeGetProductCategories($query, $filter = [])
    {
        return $query
            ->with('productGroup')
            ->where($filter)
            ->orderBy('description')
            ->orderBy(ProductGroup::select('description')->whereColumn('product_groups.id', 'product_categories.group_id'));

        // return $query->with('ProductGroup')->where('activo', 1);
    }
}
