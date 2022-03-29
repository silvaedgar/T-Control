<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable= ['sale_id','product_id','tax_id','item','quantity','price','tax'];


    public function Sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function Tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id', 'id');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
