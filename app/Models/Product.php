<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','tax_id','category_id', 'code', 'name', 'cost_price', 'sale_price',
            'stock'];


    public function ProductCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function PurchaseDetail() {
        return $this->hasMany(PurchaseDetail::class,'product_id','id');
    }

    public function SaleDetail() {
        return $this->hasMany(SaleDetail::class,'product_id','id');
    }

}
