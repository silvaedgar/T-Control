<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentClient extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','client_id', 'coin_id','payment_form_id','payment_date',
    'rate_exchange','mount','observations'];

public function Client()
{
    return $this->belongsTo(Client::class, 'client_id', 'id');
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
