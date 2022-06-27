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
        $this->middleware('role.admin');
    }

    public function index()
    {
        // busca la moneda base de calculo
        // $base_currency = Coin::where('activo','S')->('base_currency','S')->first();

        // if (isset($base_currency)) {
        // busca las relacion de precios de las monedas con la de base
        $coinvalues = CurrencyValue::where('status','Activo')->orderBy('coin_id')->get();
        // return $coinvalues;
        if (isset($coinvalues))
            return view('maintenance.currency-values.index',compact('coinvalues'));
        return view('maintenance.currency-values.index');
        // }
        // else {
        //     return view ('home');
        // }
    }

    public function create()
    {
        $base_currency = Coin::where('status','Activo')->get();
        $coins = Coin::where('status','Activo')->get();
        return view('maintenance.currency-values.create',compact('coins','base_currency'));
    }

    public function store(StoreCurrencyValueRequest $request)
    {
        DB::beginTransaction();
        try {
            CurrencyValue::where([['base_currency_id',$request->base_currency_id],['coin_id',$request->coin_id]])
                    ->orwhere([['base_currency_id',$request->coin_id],['coin_id',$request->base_currency_id]])->update(['status' => 'Inactivo']);
            CurrencyValue::create($request->all());
            $message = "Ok_Se establecieron el Precio Compra y Venta de la moneda ";
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            $message = "Error_No se pudo actualizar el  precio Compra Venta de la moneda";
        }
        return redirect()->route('maintenance.currencyvalues.index')->with('status',$message);
    }


    public function show(CurrencyValue $coinValues)
    {
        //
    }

    public function edit($coinid)
    {
        $base_currency = Coin::find($coinid);
        $coins = Coin::where('activo','S')->get();
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
