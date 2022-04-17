<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coin;
use App\Models\CurrencyValue;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\Client;

use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function product_price($id) {
        return  DB::table('products')->select('products.*','percent')
            ->join('taxes','taxes.id','products.tax_id')->where('products.id',$id)->first();
    }


    public function balancesuppliers($id) {
        $first = Supplier::select('purchase_date as date','suppliers.id as supplier','symbol','mount','rate_exchange','suppliers.name','balance','purchases.id')
        ->selectRaw("'Compras' as type")->selectRaw('mount / rate_exchange as mountbalance')
        ->join('purchases','suppliers.id','purchases.supplier_id')->join('coins','purchases.coin_id','coins.id')
        ->where('supplier_id',$id);

        return  Supplier::select('payment_date as date','suppliers.id as supplier','symbol','mount','rate_exchange','suppliers.name','balance','payment_suppliers.id')
        ->selectRaw("'Pagos' as type")->selectRaw('mount / rate_exchange  as mountbalance')
        ->join('payment_suppliers','suppliers.id','payment_suppliers.supplier_id')
        ->join('coins','payment_suppliers.coin_id','coins.id')->where('supplier_id',$id)
        ->union($first)
        ->orderBy('date','desc')->get();

        $first = Supplier::select('purchase_date as date','symbol','mount','rate_exchange','suppliers.name','balance','purchases.id')
            ->selectRaw("'Compras' as type")->selectRaw('mount / rate_exchange as mountbalance')
            ->join('purchases','suppliers.id','purchases.supplier_id')->join('coins','purchases.coin_id','coins.id')
            ->where('supplier_id',$id)->where('purchases.status','<>','Historico');

        return  Supplier::select('payment_date as date','symbol','mount','rate_exchange','suppliers.name','balance','payment_suppliers.id')
                ->selectRaw("'Pagos' as type")->selectRaw('mount / rate_exchange  as mountbalance')
                ->join('payment_suppliers','suppliers.id','payment_suppliers.supplier_id')
                ->join('coins','payment_suppliers.coin_id','coins.id')->where('supplier_id',$id)
                ->where('payment_suppliers.status','<>','Historico')->union($first)->orderBy('date','desc')->get();

    }

    public function loadcoins ($id) {
        return  Coin::select('coins.*','currency_values.purchase_price','currency_values.sale_price')
            ->join('currency_values','coins.id','currency_values.coin_id')->where('coins.status','Activo')
            ->where('currency_values.status','Activo')->where('currency_values.base_currency_id',$id)->get();
    }

    public function rate_exchange($id) {
        return  (CurrencyValue::select('currency_values.*','coins.symbol')->join('coins','currency_values.coin_id','coins.id')
            ->where('coin_id',$id)->where('currency_values.status','Activo')->first());
    }

    public function loadcategories($id) {
        return ProductCategory::where('group_id',$id)->orderby('description')->get();
    }

}
