<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Coin;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Support\Facades\DB;

use PDF;

class SupplierController extends Controller
{
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
        $request->validate ([
            'document' => "required|string|unique:suppliers,document,$supplier->id"
        ]);
        $supplier = Supplier::find($supplier->id);
        $supplier->update($request->all());
        echo $supplier;
        return redirect()->route('suppliers.index')->with("status","Ok_Proveedor $supplier->name  Actualizado con Exito");
        //
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->status = 'Inactivo';
        // $supplier->save();
        return redirect()->route('products.index')->with("status","Ok_Se elimino el producto $supplier->name con exito. ACTIVAR SAVE");
    }

    public function listprint() {

        $suppliers = Supplier::where('balance','>',0)->orderBy('name')->get();
        $pdf = PDF::loadView('suppliers.report',['suppliers' =>$suppliers]);
        // $pdf = PDF::loadHTML('<h1>Test</h1>');
        return $pdf->stream();
    }

    public function balance($id) {

        // $orders = DB::table('purchase_details')->select('id')
        //         ->selectRaw('price * ? as price_with_tax', [2])
        //         ->get();

        // $orders = DB::table('purchase_details')->whereRaw('price > IF(status = "Activo", 100, 500)', [200])->get();

        $first = Supplier::select('purchase_date','symbol','suppliers.name',DB::raw("'Compras'"))
            ->join('purchases','suppliers.id','purchases.supplier_id')->join('coins','purchases.coin_id','coins.id')
            ->where('supplier_id',$id)->get();

        $second = Supplier::select('payment_date','mount','rate_exchange','symbol','suppliers.name',DB::raw("'Pagos'"))
                ->join('payment_suppliers','suppliers.id','payment_suppliers.supplier_id')
                ->join('coins','payment_suppliers.coin_id','coins.id')->where('supplier_id',$id)->get();
        return $first->union($second);

    }

}
