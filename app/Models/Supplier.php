<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable=['user_id','document_type','document','name','contact','address','phone',
     'balance','status'];

    public function Purchase()
    {
        return $this->hasMany(Purchase::class, 'supplier_id', 'id');
    }

    public function PaymentSupplier()
    {
        return $this->hasMany(PaymentSupplier::class, 'supplier_id', 'id');
    }

}
