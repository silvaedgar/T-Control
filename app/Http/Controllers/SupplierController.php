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

class SupplierController extends Controller
{

    public function __construct() {
        $this->middleware('role');
    }

    public function index()
    {
        $symbolcoin = Coin::where('calc_currency_purchase','S')->where('status','Activo')->first();
        $suppliers = Supplier::orderBy('name')->get();
        return view('suppliers.index',compact('suppliers','symbolcoin'));
    }

    public function create()
    {
        return view('suppliers.create');
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
        $supplier = Supplier::find($supplier->id);
        return view('suppliers.edit',compact('supplier'));
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
        // $supplier->save();
        return redirect()->route('products.index')->with("status","Ok_Se elimino el proveedor  $supplier->name con exito. ACTIVAR SAVE");
    }

    public function listcreditors() {

        $suppliers = Supplier::where('balance','>',0)->orderBy('name')->get();
        $pdf = PDF::loadView('suppliers.report',['suppliers' =>$suppliers]);
        // $pdf = PDF::loadHTML('<h1>Test</h1>');
        return $pdf->stream();
    }

    public function loadmovementsuppliers($id,$mensaje) {
        if ($mensaje == 'Mostrar Historicos') {
            $first = Supplier::select('purchase_date as date','suppliers.id as supplier','symbol','mount',
                    'rate_exchange','suppliers.name','balance','purchases.id','purchases.created_at as create')
                ->selectRaw("'Compras' as type")->selectRaw('mount / rate_exchange as mountbalance')
                ->join('purchases','suppliers.id','purchases.supplier_id')->join('coins','purchases.coin_id','coins.id')
                ->where('supplier_id',$id)->where('purchases.status','<>','Historico');

            $movements = Supplier::select('payment_date as date','suppliers.id as supplier','symbol','mount',
                    'rate_exchange','suppliers.name','balance','payment_suppliers.id','payment_suppliers.created_at as create')
                ->selectRaw("'Pagos' as type")->selectRaw('mount / rate_exchange  as mountbalance')
                ->join('payment_suppliers','suppliers.id','payment_suppliers.supplier_id')
                ->join('coins','payment_suppliers.coin_id','coins.id')->where('supplier_id',$id)
                ->where('payment_suppliers.status','<>','Historico',)->union($first)
                ->orderBy('date','desc')->orderBy('create','desc')->get();
        }
        else {
            $first = Supplier::select('purchase_date as date','suppliers.id as supplier','symbol','mount',
                    'rate_exchange','suppliers.name','balance','purchases.id','purchases.created_at as create')
                ->selectRaw("'Compras' as type")->selectRaw('mount / rate_exchange as mountbalance')
                ->join('purchases','suppliers.id','purchases.supplier_id')->join('coins','purchases.coin_id','coins.id')
                ->where('supplier_id',$id);
            $movements = Supplier::select('payment_date as date','suppliers.id as supplier','symbol','mount',
                    'rate_exchange','suppliers.name','balance','payment_suppliers.id','payment_suppliers.created_at as create')
                ->selectRaw("'Pagos' as type")->selectRaw('mount / rate_exchange  as mountbalance')
                ->join('payment_suppliers','suppliers.id','payment_suppliers.supplier_id')
                ->join('coins','payment_suppliers.coin_id','coins.id')->where('supplier_id',$id)
                ->union($first)->orderBy('date','desc')->orderBy('create','desc')->get();
        }
        if (count($movements) == 0) {  // esto solo sucede cuando solo existe balance inicial
            $movements = Supplier::select('*','suppliers.id as supplier_id')->selectRaw("'Balance' as type")->where('id',$id)->get();
        }

        return $movements;
    }

    public function balance($id,$mensaje='') {

        $mensaje = ($mensaje=='' || $mensaje=='Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos');
        $movements = $this->loadmovementsuppliers($id,$mensaje);
        $backlist = true;
        return view('suppliers.balance',compact('movements','id','mensaje','backlist'));
    }

}
