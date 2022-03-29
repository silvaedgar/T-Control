<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Coin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','symbol','base_calc_coin','base_coin'];

    public function Purchase()
    {
        return $this->hasMany(Purchase::class, 'coin_id', 'id');
    }

    public function Sale()
    {
        return $this->hasMany(Sale::class, 'coin_id', 'id');
    }

    public function CoinRate() // moneda a la cual se le establece el valor
    {
        return $this->hasMany(CoinRate::class, 'coin_id', 'id');
    }

    public function CoinBase()  // relacion que indica la moneda base en el valor de la misma
    {
        return $this->hasMany(CoinRate::class, 'coin_base_id', 'id');
    }

    public function PaymentSupplier()  // relacion que indica la moneda base en el valor de la misma
    {
        return $this->hasMany(PaymentSupplier::class, 'coin_id', 'id');
    }

}
