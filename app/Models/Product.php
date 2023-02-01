<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected  static $logAttributes = ['name', 'code','cost_price','sale_price'];

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

    public function scopeGetProducts($query,$filter='') {
        if ($filter == '') {
            return $query->where('status','Activo')->orderBy('name');
        }
        return $query->where('status',$filter)->orderBy('name');
    }

}
