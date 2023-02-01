<?php

namespace App\Models;

use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\SaleDetail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Tax extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = ['user_id','percent','description','date_start'];

    public function Product()
    {
        return $this->hasMany(Product::class, 'tax_id', 'id');
    }

    public function PurchaseDetail()
    {
        return $this->hasMany(PurchaseDetail::class, 'tax_id', 'id');
    }

    public function SaleDetail()
    {
        return $this->hasMany(SaleDetail::class, 'tax_id', 'id');
    }

    // Ambito Local del Scope
    public function scopeGetTaxes($query) {
        return $query->where('status','Activo')->orderBy('percent');
    }


}
