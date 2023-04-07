<?php

namespace App\Http\Controllers;

use App\Models\CurrencyValue;
use App\Models\Coin;

use App\Facades\Config;

use App\Http\Requests\CurrencyValueRequest;

use App\Traits\CurrencyValueTrait;
use App\Traits\CoinTrait;

class CurrencyValueController extends Controller
{
    use CurrencyValueTrait, CoinTrait;

    public function __construct()
    {
        // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('role.admin');
    }

    public function index()
    {
        $config = Config::labels('CurrencyValue', CurrencyValue::orderBy('date_value', 'desc')->get());

        $config['header']['title'] = 'Relacion Valor Costo-Venta de Monedas';
        $config['isFormIndex'] = true;
        $config['reverse'] = true;
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $baseCurrency = $this->getCoins()->get();
        $coins = $this->getCoins()->get();

        $config = Config::labels('CurrencyValue');

        $config['header']['title'] = 'Establecer Precio Compra-Venta entre Monedas';
        return view('maintenance.shared.create-edit', compact('coins', 'baseCurrency', 'config'));
    }

    public function store(CurrencyValueRequest $request)
    {
        return redirect()
            ->route('maintenance.currencyvalues.index')
            ->with('status', $this->storeCurrencyValue($request->toArray()));
    }
}
