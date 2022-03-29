<?php

namespace App\Http\Controllers;

use App\Models\CurrencyValue;
use App\Models\Coin;
use App\Http\Requests\StoreCurrencyValueRequest;
use App\Http\Requests\UpdateCurrencyValueRequest;
use \Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CurrencyValueController extends Controller
{
    public function __construct() {      // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('can:maintenance');
    }

    public function index()
    {
        // busca la moneda base de calculo
        $base_currency = Coin::where('base_currency','S')->where('status','Activo')->first();

        if (isset($base_currency)) {
        // busca las relacion de precios de las monedas con la de base
            $coinvalues = CurrencyValue::where('base_currency_id',$base_currency->id)
                    ->where('status','Activo')
                    ->orderBy('coin_id')->get();
            if (isset($coinvalues))
                return view('maintenance.currency-values.index',compact('coinvalues','base_currency'));
            return view('maintenance.currency-values.index',compact('base_currency'));
        }
        else {
            return view ('home');
        }
    }

    public function create()
    {
    }

    public function store(StoreCurrencyValueRequest $request)
    {
        DB::beginTransaction();
        try {
            CurrencyValue::where('base_currency_id','=',$request->base_currency_id)
                    ->where('coin_id','=',$request->coin_id)
                    ->update(['status' => 'Inactivo']);
            CurrencyValue::create($request->all());

            // $base_currency = Coin::where('base_currency','S')->where('status','=','Activo')->first();
            // if (isset($base_currency)) {
            //     $coinvalues = CurrencyValue::where('base_currency_id','=',$base_currency->id)
            //             ->where('status','Activo')
            //             ->orderBy('coin_id')->get();
            // }
            $message = "Ok_Se establecieron el Precio Compra y Venta de la moneda ";
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            $message = "Error_No se pudo actualizar el  precio Compra Venta de la moneda";
        }
        echo $message;
        return redirect()->route('maintenance.currencyvalues.index')->with('status',$message);
    }


    public function show(CurrencyValue $coinValues)
    {
        //
    }

    public function edit($coinid)
    {
        $base_currency = Coin::find($coinid);
        $coins = Coin::where('status','Activo')->get();
        return view('maintenance.currency-values.edit',compact('coins','base_currency'));
    }

    public function update(UpdateCurrencyValueRequest $request, CurrencyValue $coinValues)
    {
        //
    }

    public function destroy(CurrencyValue $coinValues)
    {
        //
    }
}
