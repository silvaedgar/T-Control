<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSupplier extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','supplier_id', 'coin_id','payment_form_id','payment_date',
        'rate_exchange','mount','details'];

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function Coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    public function PaymentForm()
    {
        return $this->belongsTo(PaymentForm::class, 'payment_form_id', 'id');
    }

}
