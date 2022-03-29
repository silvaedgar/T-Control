<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coin;
use App\Models\CurrencyValue;
use App\Models\ProductCategory;


class ApiController extends Controller
{
    public function loadcoins ($id) {
        return  Coin::select('coins.*','currency_values.purchase_price','currency_values.sale_price')
            ->join('currency_values','coins.id','currency_values.coin_id')->where('coins.status','Activo')
            ->where('currency_values.status','Activo')->where('currency_values.base_currency_id',$id)->get();
    }

    public function rate_exchange($id) {
        return  (CurrencyValue::where('coin_id',$id)->where('status','Activo')->first());
    }

    public function loadcategories($id) {
        return ProductCategory::where('group_id','=',$id)->get();
    }

}
