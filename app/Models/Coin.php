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

// busca la moneda base y la moneda de calculo si devuelve un record son las mismas de lo contrario devuelve 2
    public function scopeGetBaseCoins($query,$calc_coin) {
        return Coin::select('*')->selectRaw('1 as purchase_price')->selectRaw('1 as sale_price')
            ->where($calc_coin,'S')->where('status','Activo');
    }

    // Funcion que extrae las monedas asociadas con sus valores asociados a la moneda base (de calculo o de  base)
    public function scopeGetCurrencyCalcValue($query,$id,$fieldjoin,$fieldwhere) {
        return $query->select('coins.*','currency_values.purchase_price','currency_values.sale_price')
            ->join('currency_values','coins.id',$fieldjoin)->where('coins.status','Activo')
            ->where('currency_values.status','Activo')->where($fieldwhere,$id);

        //// esta consulta en comentario es la que debo hacer para que funcione como es y arreglar
        // en payment clients un parametro mirar la calcvalue1
            // return $query->select('coins.*','currency_values.purchase_price','currency_values.sale_price')
            // ->join('currency_values','coins.id','currency_values.coin_id')
            // ->where([['coins.status','Activo'],['currency_values.status','Activo']])
            // ->where( function ($q) use($id) { $q->where('currency_values.base_currency_id',$id)
            //         ->orwhere('currency_values.coin_id',$id); });

    }

    // public function scopeGetCurrencyCalcValue1($query,$id) {
    //     return $query->select('coins.*','currency_values.purchase_price','currency_values.sale_price')
    //         ->join('currency_values','coins.id','currency_values.coin_id')->where('coins.status','Activo')
    //         ->where('currency_values.status','Activo')->where('currency_values.coin_id',$id);
    // }
}
