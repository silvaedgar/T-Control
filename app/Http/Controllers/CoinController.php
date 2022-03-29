<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Http\Requests\StoreCoinRequest;
use App\Http\Requests\UpdateCoinRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use \Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CoinController extends Controller
{

    public function __construct() {      // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('can:maintenance');
    }

    public function index()
    {
        $coins = Coin::where('status','Activo')->get();
        return view('maintenance.coins.index',compact('coins'));
    }

    public function create()
    {
      return view('maintenance.coins.create');
        //
    }

    public function store(StoreCoinRequest $request)
    {
        Coin::create($request->all());
        return redirect()->route('maintenance.coins.index')->with('status',"Ok_Creación de Moneda $request->name");
    }
    public function show(Coin $coin)
    {
        return view('maintenance.coins.index');
        //
    }

    public function edit($id)
    {
        $coin = Coin::find($id);
        return view('maintenance.coins.edit',compact('coin'));
        //
    }

    public function update(Request $request)
    {
        $request->validate ([
            'name' => "required|unique:coins,name,$request->id",
            'symbol' => ["required","max:3",Rule::unique('coins')->ignore($request->id)]
        ]);
        // if ($request->base_coin == 'S')
        // {

        // }
        $coin = Coin::find($request->id);
        $coin->update($request->all());
        return redirect()->route('maintenance.coins.index')->with('status',"Ok_Actualización de Moneda $request->name");
    }

    public function destroy($id)
    {
        $coin = Coin::find($id);
        $coin->status = 'Inactivo';
        $coin->save();
        return redirect()->route('maintenance.coins.index')->with('status',"Ok_Eliminar Moneda $coin->name satisfactorio");

        //
    }
}
