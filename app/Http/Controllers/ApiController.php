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
    public function balancesuppliers($id) {

        // $orders = DB::table('purchase_details')->select('id')
        //         ->selectRaw('price * ? as price_with_tax', [2])
        //         ->get();

        // $orders = DB::table('purchase_details')->whereRaw('price > IF(status = "Activo", 100, 500)', [200])->get();

        $first = Supplier::select('purchase_date','symbol','suppliers.name',DB::raw("'Supplier'"))
            ->join('purchases','suppliers.id','purchases.supplier_id')->join('coins','purchases.coin_id','coins.id')
            ->where('supplier_id',$id)->get();

        $second = Supplier::select('payment_date','mount','rate_exchange','symbol','suppliers.name',DB::raw("'Client'"))
                ->join('payment_suppliers','suppliers.id','payment_suppliers.supplier_id')
                ->join('coins','payment_suppliers.coin_id','coins.id')->where('supplier_id',$id)->get();
        // $a = Supplier::select('name',DB::raw("'Suppliers'"))->get();
        // $b = Client::select('names',DB::raw("'Clients'"))->get();
        return $first->union($second);

        // return $orders;
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
