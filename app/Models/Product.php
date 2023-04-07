<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory;
    // , LogsActivity;

    // protected static $logAttributes = ['name', 'code', 'cost_price', 'sale_price'];

    protected $fillable = ['user_id', 'tax_id', 'category_id', 'code', 'name', 'cost_price', 'sale_price', 'stock'];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function purchaseDetail()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function saleDetail()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function scopeGetProducts($query, $filter = [['activo', '=', 1]])
    {
        return $query
            ->with('tax')
            ->where($filter)
            ->orderBy('name');
        // if ($filter == '') {
        //     return $query->with('tax')->where('activo', 1)->orderBy('name');
        // }
        // return $query->with('tax')->where('activo', $filter)->orderBy('name');
    }
}
