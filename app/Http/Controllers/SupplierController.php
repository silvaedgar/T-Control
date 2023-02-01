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
use App\Facades\DataCommonFacade;


class SupplierController extends Controller
{
    use GetDataCommonTrait;

    public function __construct() {
        $this->middleware('role');
    }

    public function index()
    {
        $base_coin = $this->get_base_coin('calc_currency_sale')->first();
        $data = ['base_coin' => $base_coin,'header' =>'Listado de Proveedores'];
        $data_common = DataCommonFacade::index('Supplier',$data);
        $suppliers = Supplier::GetSuppliers()->get();
        return view('suppliers.index',compact('suppliers','data_common'));
    }

    public function create()
    {
        $data_common = DataCommonFacade::create('Supplier',['header' =>'Creando Proveedor']);
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
        $data_common = DataCommonFacade::edit('Supplier',['header' =>'Editando Proveedor']);
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

    public function list_creditors() {

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
            $movements = Supplier::select('*','suppliers.id as supplier_id')->selectRaw("'Balance' as type")->where('id',$id)->get();
        }
        return $movements;
    }

    public function balance($supplier_id,$mensaje='') {

        $supplier = Supplier::find($supplier_id);
        $data = $this->generate_data_coin('calc_currency_purchase');
        $mensaje = ($mensaje=='' || $mensaje=='Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos');
        $movements = $this->load_movements_supplier($supplier->id,$data['calc_coin']->id,$data['base_coin']->id,$mensaje);
        $message_balance = 'Saldo: '.$movements[0]->balance." ".$data['calc_coin']->symbol;

        // la linea de abajo es si se queire poner los Bs para cuando el calculo sean en otra moneda no lo uso no me parece. la tasa depende del proveedor
        // $message_balance .= ($calc_coin->symbol != $base_coin->symbol ? ' - '.number_format($movements[0]->balance * $rate->sale_price,2).$base_coin->symbol:'');

        $data["supplier"] = $movements[0]->name;
        $data["message_balance"] = $message_balance;
        $data["header"] = "Detalle de Movimientos";
        $data_common = DataCommonFacade::balance('Supplier',$data);
        $base_coin = $data['base_coin'];
        // return $movements;
        return view('suppliers.balance',compact('movements','mensaje','base_coin','data_common'));
    }

}
