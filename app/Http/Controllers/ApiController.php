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

    public function search_invoice_client($id,$calc_coin_id,$base_coin_id) {
// Busca las facturas asociadas a un cliente para generar un pago
        return  Client::GetDataSales($id,$calc_coin_id,$base_coin_id)->select('sales.*','coins.symbol as symbol','clients.balance','clients.count_in_bs')
                ->orderBy('sales.sale_date')->get();
    }


    public function search_invoice_supplier($id,$calc_coin_id,$base_coin_id) {
        return Supplier::GetDataPurchases($id,$calc_coin_id,$base_coin_id)->select('purchases.*','coins.symbol as symbol','suppliers.balance','suppliers.name')
                ->orderBy('purchases.purchase_date')->get();
    }

    public function loadcoins ($id) {
        return  Coin::select('coins.*','currency_values.purchase_price','currency_values.sale_price')
            ->join('currency_values','coins.id','currency_values.coin_id')->where('coins.status','Activo')
            ->where('currency_values.status','Activo')->where('currency_values.base_currency_id',$id)->get();
    }

    public function rate_exchange($id) {
        // return  (CurrencyValue::select('currency_values.*','coins.symbol')->join('coins','currency_values.coin_id','coins.id')
        //     ->where([['coin_id',$id],['currency_values.status','Activo']])->orwhere([['base_currency_id',$id],['currency_values.status','Activo']])
        //     ->get());

        return  (CurrencyValue::select('currency_values.*','coins.symbol')->join('coins','currency_values.coin_id','coins.id')
            ->where([['coin_id',$id],['currency_values.status','Activo']])->orwhere([['base_currency_id',$id],['currency_values.status','Activo']])
            ->first());
    }

    public function loadcategories($id) {
        return ProductCategory::with('ProductGroup')->where('id',$id)->first();
    }

}
