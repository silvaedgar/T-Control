<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Http\Requests\StoreCoinRequest;

use App\Facades\Config;

use App\Traits\CoinTrait;
use App\Traits\SharedTrait;

class CoinController extends Controller
{
    use CoinTrait, SharedTrait;

    public function __construct()
    {
        // Manera de proteger ruta en RoleController hay otra forma

        $this->middleware('role.admin');
    }

    public function index()
    {
        $config = Config::labels('Coins', $this->getCoins()->get());
        $config['header']['title'] = 'Listado de Monedas';
        $config['isFormIndex'] = true;

        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $config = Config::labels('Coins');
        $config['header']['title'] = 'Creando Moneda';
        return view('maintenance.shared.create-edit', compact('config'));
    }

    public function store(StoreCoinRequest $request)
    {
        return $this->saveCoin($request);

        // Coin::create($request->all());
        // return redirect()
        //     ->route('maintenance.coins.index')
        //     ->with('status', "Ok_Creación de Moneda $request->name");
    }

    public function edit(Coin $coin)
    {
        $config = Config::labels('Coins', $coin, true);
        $config['header']['title'] = 'Editando Moneda: ' . $coin->name;
        return view('maintenance.shared.create-edit', compact('coin', 'config'));
    }

    public function update(StoreCoinRequest $request)
    {
        return $this->saveCoin($request);

        // $coin = Coin::find($request->id);
        // $coin->update($request->all());
        // return redirect()
        //     ->route('maintenance.coins.index')
        //     ->with('status', "Ok_Actualización de Moneda $request->name");
    }

    public function destroy(Coin $coin)
    {
        $coin->activo = !$coin->activo;
        $coin->save();
        return redirect()
            ->route('maintenance.coins.index')
            ->with('status', (!$coin->activo ? 'Ok_Eliminación' : 'Ok_Activacion') . " de la Moneda $coin->name");
    }
}
