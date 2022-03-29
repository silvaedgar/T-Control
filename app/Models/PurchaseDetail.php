<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable= ['purchase_id','product_id','tax_id','item','quantity','price','tax'];


    public function Purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
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
