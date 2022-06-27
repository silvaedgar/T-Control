<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\CurrencyValue;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use \Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class CoinBaseController extends Controller
{

    public function __construct() {      // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('role.admin');
    }

    public function index()
    {
        // $basecoin = Coin::where('base_currency','S')->first(); // moneda base
        $calc_purchase = Coin::GetBaseCoins('calc_currency_purchase')->first(); // moneda base de calculo compras
        $calc_sale = Coin::GetBaseCoins('calc_currency_sale')->first(); // moneda base de calculo ventas
        $coins = Coin::where('status','Activo')->get();
        return view('maintenance.coins.base',compact('calc_purchase','coins','calc_sale'));
    }

    private function FindRateExchange($currency_calc_old,$currency_calc_new,$price) {
        $columna = "$price as precio";
        $coin_values = CurrencyValue::select($columna)->where('base_currency_id',$currency_calc_old)
                    ->where('coin_id',$currency_calc_new)->where('status','Activo')->first();
        return (isset($coin_values) ? $coin_values->precio : -1);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            // $basecoin = Coin::GetCoinBase()->first(); // moneda base
            $calccoinpurchase = Coin::GetBaseCoins('calc_currency_purchase')->first(); // moneda base de calculo compras
            $calccoinsale = Coin::GetBaseCoins('calc_currency_sale')->first(); // moneda base de calculo ventas
/// -----------------------------------------------------------------------------------------------
//  Vienen las actualizaciones de los balances ojo cuando hay mas monedas hay que revisar si es * o /
            if ($calccoinpurchase != '')
                if ($calccoinpurchase->id != $request->calc_currency_purchase) {
                    $rate_exchange = $this->FindRateExchange($calccoinpurchase->id,$request->calc_currency_purchase,
                            'purchase_price');
                    if ($rate_exchange < 0) {
                        DB::rollback();
                        return redirect()->route('coinbase.index')->with('status',"Error_No existe valor de divisa relacionado entre la anterior y la nueva. Verifique");
                    }
                    Supplier::update([
                        'balance' => balance * $rate_exchange
                    ]);
                    // DB::update('UPDATE clients SET balance = balance * '.$rate_exchange);
                }
            if ($calccoinsale != '')
                if ($calccoinsale->id != $request->calc_currency_sale) {
                    $coins =  Coin::GetCurrencyCalcValue($request->calc_currency_sale,'currency_values.base_currency_id','currency_values.coin_id')
                        ->union(Coin::GetCurrencyCalcValue($request->calc_currency_sale,'currency_values.coin_id','currency_values.base_currency_id'))
                        ->first();
                    if ($coins->sale_price < 0) {
                        DB::rollback();
                        return redirect()->route('coinbase.index')->with('status',"Error_No existe valor de divisa relacionado entre la anterior y la nueva. Verifique");
                    }
                    // Client::update([
                    //     'balance' => balance / $coins->sale_price
                    // ]);

                    // DB::update('UPDATE clients SET balance = balance * '.$rate_exchange);
                }
            // Deja sin moneda base el modelo
            // Coin::where('base_currency','S')->update(['base_currency'=> 'N']);
            Coin::where('calc_currency_purchase','S')->update(['calc_currency_purchase'=> 'N']);
            Coin::where('calc_currency_sale','S')->update(['calc_currency_sale'=> 'N']);
            // actualiza las monedas base
            // Coin::where('id',$request->base_currency)->update(['base_currency' => 'S']);
            Coin::where('id',$request->calc_currency_purchase)->update(['calc_currency_purchase' => 'S']);
            Coin::where('id',$request->calc_currency_sale)->update(['calc_currency_sale' => 'S']);
            DB::commit();
            $message = "Ok_Se actualizaron las monedas base y de calculo";
        } catch (\Throwable $th) {
            DB::rollback();
            $message = "Error_Error en el procedimiento de actualización. Contacte al Administrador";
        }
        return redirect()->route('coinbase.index')->with('status',$message);
    }
}
