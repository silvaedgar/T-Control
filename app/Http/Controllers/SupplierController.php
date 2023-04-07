<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Coin;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;

// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Auth;
use App\Facades\Config;

// use App\Facades\DataCommonFacade;

use App\Traits\CoinTrait;
use App\Traits\SupplierTrait;
use App\Traits\SharedTrait;

use PDF;

class SupplierController extends Controller
{
    use CoinTrait, SupplierTrait, SharedTrait;

    public function __construct()
    {
        $this->middleware('role');
    }

    public function index()
    {
        $config = Config::labels('Suppliers', $this->getSuppliers()->get());
        $config['header']['title'] = 'Listado de Proveedores';
        $config['data']['baseCoin'] = $this->getBaseCoin('base_currency')->first();
        // $config['data']['calcCoin'] = $this->getBaseCoinRate($this->getBaseCoin('base_currency')->first()->id)->first();
        $config['data']['calcCoin'] = $this->getBaseCoin('calc_currency_purchase')->first();
        $config['isFormIndex'] = true;
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $config = Config::labels('Suppliers');
        $config['header']['title'] = 'Creando Proveedor';
        $config['cols'] = 2;
        return view('shared.create-edit', compact('config'));
    }

    public function store(StoreSupplierRequest $request)
    {
        return redirect()
            ->route('suppliers.index')
            ->with('message_status', $this->saveSupplier($request)['message']);
    }

    public function edit(Supplier $supplier)
    {
        $config = Config::labels('Suppliers', $supplier, true);
        $config['header']['title'] = 'Editando Proveedor: ' . $supplier->name;
        $config['cols'] = 2;
        return view('shared.create-edit', compact('config', 'supplier'));
    }

    public function update(StoreSupplierRequest $request)
    {
        return redirect()
            ->route('suppliers.index')
            ->with('message_status', $this->saveSupplier($request)['message']);
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier->balance != 0) {
            return redirect()
                ->route('suppliers.index')
                ->with('status', "Error_Proveedor  $supplier->name tiene saldo pendiente no se elimino");
        }
        $supplier->activo = !$supplier->activo;
        $supplier->save();
        return redirect()
            ->route('suppliers.index')
            ->with('status', "Ok_Se elimino el proveedor  $supplier->name con exito.");
    }

    public function list_creditors()
    {
        $suppliers = $this->getSuppliers([['balance', '<>', 0]])->get();
        // Supplier::Balance('<>', 0)->orderBy('name')->get();  esta en el scope
        $pdf = PDF::loadView('suppliers.report', ['suppliers' => $suppliers]);
        return $pdf->stream();
    }

    public function load_movements_supplier($id, $calc_coin_id, $base_coin_id, $mensaje = '')
    {
        $first = Supplier::GetDataPurchases($id, $calc_coin_id, $base_coin_id)->where('purchases.status', '<>', 'Anulada');
        $movements = Supplier::GetDataPayments($id, $calc_coin_id, $base_coin_id)->where('payment_suppliers.status', '<>', 'Anulado');
        if ($mensaje == 'Mostrar Historicos') {
            $first = $first->where('purchases.status', '<>', 'Historico');
            $movements = $movements->where('payment_suppliers.status', '<>', 'Historico');
        }
        $movements = $movements
            ->union($first)
            ->orderBy('date', 'desc')
            ->orderBy('create', 'desc')
            ->get();
        if (count($movements) == 0) {
            // esto solo sucede cuando solo existe balance inicial
            $movements = Supplier::select('*', 'suppliers.id as supplier_id')
                ->selectRaw("'Balance' as type")
                ->where('id', $id)
                ->get();
        }
        return $movements;
    }

    public function loadMovementSupplier($supplier, $data, $mensaje)
    {
        $purchases = $supplier->purchase;
        $payments = $supplier->paymentSupplier;
        return $payments->union($purchases)->get();

        //  Supplier::GetDataPurchases($id, $calc_coin_id, $base_coin_id)->where('purchases.status', '<>', 'Anulada');
        $movements = $payments
            ->union($purchases)
            ->orderBy('date', 'desc')
            ->orderBy('create', 'desc')
            ->get();
        if (count($movements) == 0) {
            // esto solo sucede cuando solo existe balance inicial
            $movements = Supplier::select('*', 'suppliers.id as supplier_id')
                ->selectRaw("'Balance' as type")
                ->where('id', $id)
                ->get();
        }
        return $movements;
    }

    public function balance(Supplier $supplier, $mensaje = '')
    {
        // $supplier = Supplier::with('purchase', 'purchase.coin', 'paymentSupplier', 'paymentSupplier.coin')
        //     ->where('id', $supplier->id)->first();
        $mensaje = $mensaje == '' || $mensaje == 'Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos';

        // $mensaje = $mensaje == '' || $mensaje == 'Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos';
        // $config = Config::labels('Clients', $client);
        // $response = $this->loadConfigBalance($client, $mensaje, $config);
        // $config = $response['config'];
        // $movements = $response['movements'];
        // $pdf = PDF::loadView('clients.printbalance', ['movements' => $movements, 'config' => $config]);
        // return $pdf->stream();

        // $config = $this->getDisplayInfoSupplier();
        $config = Config::labels('Suppliers', $supplier, true);
        $config['header']['title'] = 'Detalle de Movimientos ';
        $config['buttons'] = [];
        $config['cols'] = 3;
        $rate = $this->getBaseCoinRate($this->getBaseCoin('calc_currency_purchase')->first()->id)->first();
        $config['data']['calcCoin'] = $this->getBaseCoin('calc_currency_purchase')->first();
        $config['data']['baseCoin'] = $this->getBaseCoin('base_currency')->first();
        $config['data']['calcCoin']->purchase_price = $rate->purchase_price;
        $config['data']['calcCoin']->rate = $rate->purchase_price;
        $movements = $this->load_movements_supplier($supplier->id, $config['data']['calcCoin']->id, $config['data']['baseCoin']->id, $mensaje);
        $config['isFormIndex'] = true;
        $config['header']['subTitle2'] = 'Saldo: ' . $movements[0]->balance . ' ' . $config['data']['calcCoin']->symbol;
        $config['header']['title2'] = 'Proveedor: ' . $movements[0]->name;
        $config['header']['subTitle'] = 'Moneda de Calculo ' . $config['data']['calcCoin']->symbol . ' -- Tasa ' . $config['data']['calcCoin']->purchase_price . $config['data']['baseCoin']->symbol;
        return view('suppliers.balance', compact('movements', 'mensaje', 'config'));
    }
}
