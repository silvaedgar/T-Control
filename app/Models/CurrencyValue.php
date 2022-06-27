<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyValue extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','coin_id','base_currency_id','purchase_price','sale_price'];

    public function Coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    public function BaseCurrency()
    {
        return $this->belongsTo(Coin::class, 'base_currency_id', 'id');
    }


}
