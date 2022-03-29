<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Coin;
use \Illuminate\Support\Facades\DB;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;

class PurchaseController extends Controller
{
    public function suppliers($id) {
        return  Supplier::select('purchases.*','suppliers.balance','coins.symbol')->leftjoin('purchases','suppliers.id','purchases.supplier_id')
            ->leftjoin('coins','purchases.coin_id','coins.id')->where('suppliers.id',$id)->get();


            // ->where([['supplier_id',$id],['purchases.status','Pendiente']])
            // ->orwhere([['supplier_id',$id],['purchases.status','Parcial']])->orderBy('purchase_date')->orderBy('purchases.id')
            // ->get();


        // return  Purchase::select('purchases.*','coins.symbol')->join('coins','purchases.coin_id','coins.id')
        //     ->where([['supplier_id',$id],['purchases.status','Pendiente']])
        //     ->orwhere([['supplier_id',$id],['purchases.status','Parcial']])->orderBy('purchase_date')->orderBy('purchases.id')
        //     ->get();
    }

    public function index()
    {
        $purchases = Purchase::orderBy('purchase_date','desc')->orderBy('id')->get();
        return view('purchases.index',compact('purchases'));
        //
    }

    public function create()
    {
        $products = Product::where('status','Activo')->orderBy('name')->get();
        $suppliers = Supplier::where('status','Activo')->get();
        $base = Coin::where('calc_currency_purchase','S')->orwhere('base_currency','S')
                ->where('status','=','Activo')->orderBy('base_currency')->get();
        if (count($base) > 2) {
            $message = 'Error_Verifique la Configuración de las Monedas. Consulte con el administrador';
            $purchases = Purchase::orderBy('id','desc')->get();
            return view('purchases.index',compact('purchases','message'));
        }
        $base_coins = ['base_id' => $base[0]->id, 'base_name' => $base[0]->name,'base_calc_id'=>
                isset($base[1]->id)?$base[1]->id:$base[0]->id, 'base_calc_name'=> isset($base[1]->name)?$base[1]->name:$base[0]->name];
        return view('purchases.create',compact('suppliers','products','base_coins'));
    }

    public function store(StorePurchaseRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->status = ($request->conditions == "Credito" ? 'Pendiente' : 'Cancelada');

            $purchase = Purchase::create($request->all());
            $supplier = Supplier::find($request->supplier_id);

            $amount = $request->mount;
            if($request->conditions == "Credito") {
                if ($request->rate_exchange <> 1) {
                    $amount = $request->mount / $request->rate_exchange;
                }
                $balance_supplier = $supplier->balance + $amount;
                $supplier->update([
                    'balance' => $balance_supplier,
                ]);
            }
            $item = 1;
            foreach ($request->product_id as $key => $value) {
                $results[] = array("product_id"=>$request->product_id[$key], "tax_id"=>$request->tax_id[$key],
                     "item"=>$item,"quantity"=>$request->quantity[$key], "price"=>$request->price[$key],
                     "tax"=>$request->tax[$key]);
                $product = Product::find($request->product_id[$key]);
                $product->cost_price = $request->price[$key];
                $product->save();
                $item++;
            }
            $purchase->purchase_details()->createMany($results);

            $message = 'Ok_Factura de Compra creada con exito';
            DB::commit();
        } catch (\Throwable $th) {
            echo $th;
            DB::rollback();
            $message =  'Error_Error generando Factura de Compra verifique';
        }
        // echo $message;
        return redirect()->route('purchases.index')->with('status',$message);

    }

    public function show($id)
    {
        $suppliers = Supplier::where('status','=','Activo')->get();
        $coins = Coin::where('status','Activo')->get();
        $purchase = Purchase::select('purchases.*','suppliers.name as proveedor','coins.name as moneda','coins.symbol as simbolo')
                ->join('suppliers','purchases.supplier_id','suppliers.id')
                ->join('coins','purchases.coin_id','coins.id')
                ->where('purchases.id',$id)->first();
        $purchase_details = PurchaseDetail::select('purchase_details.*','products.name')
            ->join('products','purchase_details.product_id','products.id')
            ->where('purchase_details.purchase_id',$id)
            ->where('purchase_details.status','Activo')->orderBy('item')->get();

        return view('purchases.show',compact('purchase','purchase_details','suppliers','coins'));

    }

    public function edit(Purchase $purchase)
    {
        //
    }

    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        //
    }

    public function destroy(Purchase $purchase)
    {
        //
    }
}
