<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Coin;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Traits\GetDataCommonTrait;

class SupplierController extends Controller
{
    use GetDataCommonTrait;

    public function __construct() {
        $this->middleware('role');
    }

    public function index()
    {
        $base_coin = $this->get_base_coin('calc_currency_sale')->first();
        $data_common = ['header' => 'Listado de Proveedores','base_coin_symbol'=> $base_coin->symbol,
            'buttons' => [['message' => 'Listado','icon' => 'print','url' => '','target'=>false],
                ['message' => 'Deudores','icon' => 'print','url' => route('suppliers.listcreditors'),'target'=>true],
                ['message' => 'Crear','icon' => 'person_add','url' => route('suppliers.create'),'target' => false]],
            'links_create' => [['message' => 'Crear Factura', 'url' =>route('purchases.create')],
                    ['message' => 'Generar Pago a Proveedor', 'url' =>route('paymentsuppliers.create')]],
            'links_header' => ['message' => '','url' => ''],
            'rate' => 0, 'cols' => 2,
            'controller' => 'Supplier'];

        $suppliers = Supplier::GetSuppliers()->get();
        return view('suppliers.index',compact('suppliers','data_common'));
    }

    public function create()
    {
        $base_coin = $this->get_base_coin('calc_currency_sale')->first();
        $mensaje = ($mensaje=='' || $mensaje=='Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos');
        $data_common = ['header' => 'Crear Proveedor', 'sub_header' => "", 'base_coin_id' => 0,
            'base_coin_symbol'=> '', 'rate' => 0, 'message_title' => '','message_subtitle' => '',
            'controller' => 'Client', 'buttons' => [], 'cols' => 2,
            'links_header' => ['message' => 'Ir al Listado','url' => route('suppliers.index')],
            'links_create' => [['message' => 'Crear Factura', 'url' =>route('purchases.create')],
                    ['message' => 'Generar Pago a Proveedor', 'url' =>route('paymentsuppliers.create')]]];

        return view('suppliers.create',compact('data_common'));
    }

    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create($request->all());
        return redirect()->route('suppliers.index')->with("status","Ok_Proveedor $supplier->name Creado con Exito");
    }

    public function show(Supplier $supplier)
    {
        //
    }
    public function edit(Supplier $supplier)
    {
        $data_common = ['header' => 'Editando Proveedor', 'sub_header' => "", 'base_coin_id' => 0,
            'base_coin_symbol'=> '', 'rate' => 0, 'message_title' => '','message_subtitle' => '',
            'controller' => 'Client', 'buttons' => [], 'cols' => 2,
            'links_header' => ['message' => 'Ir al Listado','url' => route('suppliers.index')],
            'links_create' => [['message' => 'Crear Factura', 'url' =>route('purchases.create')],
                    ['message' => 'Generar Pago a Proveedor', 'url' =>route('paymentsuppliers.create')]]];

        // $supplier = Supplier::find($supplier->id);
        return view('suppliers.edit',compact('supplier','data_common'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier = Supplier::find($supplier->id);
        $supplier->update($request->all());
        return redirect()->route('suppliers.index')->with("status","Ok_Proveedor $supplier->name  Actualizado con Exito");
        //
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier->balance !=0)
            return redirect()->route('products.index')->with("status","Error_Proveedor  $supplier->name tiene saldo pendiente no se elimino");

        $supplier->status = 'Inactivo';
        $supplier->save();
        return redirect()->route('products.index')->with("status","Ok_Se elimino el proveedor  $supplier->name con exito.");
    }

    public function listcreditors() {

        $suppliers = Supplier::Balance('<>',0)->orderBy('name')->get();
        $pdf = PDF::loadView('suppliers.report',['suppliers' =>$suppliers]);
        return $pdf->stream();
    }

    public function load_movements_supplier($id,$calc_coin_id,$base_coin_id,$mensaje="") {
        $first = Supplier::GetDataPurchases($id,$calc_coin_id,$base_coin_id)->where('purchases.status','<>','Anulada');
        $movements = Supplier::GetDataPayments($id,$calc_coin_id,$base_coin_id)->where('payment_suppliers.status','<>','Anulada');
        if ($mensaje == 'Mostrar Historicos') {
            $first = $first->where('purchases.status','<>','Historico');
            $movements = $movements->where('payment_suppliers.status','<>','Historico');
        }
        $movements = $movements->union($first)->orderBy('date','desc')->orderBy('create','desc')->get();
        if (count($movements) == 0) {  // esto solo sucede cuando solo existe balance inicial
            $movements = Supplier::select('*','suppliers.id as supplier')->selectRaw("'Balance' as type")->where('id',$id)->get();
        }
        return $movements;
    }

    public function balance(Supplier $supplier,$mensaje='') {

        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin = $this->get_base_coin('calc_currency_purchase')->first();
        $rate = $this->get_base_coin_rate($calc_coin->id)->first();
        $mensaje = ($mensaje=='' || $mensaje=='Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos');
        $movements = $this->load_movements_supplier($supplier->id,$calc_coin->id,$base_coin->id,$mensaje);
        $message_balance = 'Saldo: '.$movements[0]->balance." ".$calc_coin->symbol;
        // $message_balance .= ($calc_coin->symbol != $base_coin->symbol ? ' - '.number_format($movements[0]->balance * $rate->sale_price,2).$base_coin->symbol:'');

        $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
            'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol, 'rate' => $rate->purchase_price,
            'controller' => 'Supplier', 'header' => 'Detalle de Movimientos',
            'sub_header' => 'Moneda de Calculo: '.$calc_coin->symbol,
            'message_title' => 'Proveedor: '.$movements[0]->name,
            'message_subtitle' => $message_balance,
            'links_header' => ['message' => 'Listado de Proveedores','url' => route('suppliers.index')],
            'links_create' => [['message' => 'Crear Factura', 'url' =>route('purchases.create')],
                    ['message' => 'Generar Pago a Proveedor', 'url' =>route('paymentsuppliers.create')]],
            'cols'=> 3];
        // return response()->json([
        //     'movimientos' => $movements,
        //     'data' => $data_common,
        // ], 200);
        return view('suppliers.balance',compact('movements','mensaje','base_coin','data_common'));
    }

}
