<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PaymentSupplier;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Coin;
use \Illuminate\Support\Facades\DB;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;

class PurchaseController extends Controller
{
    public function __construct() {
        $this->middleware('role');
    }

    public function suppliers($id) {
        return  Supplier::select('purchases.*','suppliers.balance','coins.symbol')->leftjoin('purchases','suppliers.id','purchases.supplier_id')
            ->leftjoin('coins','purchases.coin_id','coins.id')->where('suppliers.id',$id)
            ->orderBy('name')->get();


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
        $purchases = Purchase::orderBy('purchase_date','desc')->orderBy('created_at','desc')->get();
        return view('purchases.index',compact('purchases'));
        //
    }

    public function loadcoinbase()
    {
        $base = Coin::where('calc_currency_purchase','S')->orwhere('base_currency','S')
                ->where('status','Activo')->orderBy('base_currency')->get();
        if (count($base) > 2) {
            $message = 'Error_Verifique la Configuración de las Monedas. Consulte con el administrador';
            $purchases = Purchase::orderBy('id','desc')->get();
            return '';
        }
        $base_coins = ['base_id' => $base[0]->id, 'base_name' => $base[0]->name, 'base_symbol' => $base[0]->symbol,
        'base_calc_id'=> isset($base[1]->id)?$base[1]->id:$base[0]->id,
        'base_calc_name'=> isset($base[1]->name)?$base[1]->name:$base[0]->name,
        'base_calc_symbol'=> isset($base[1]->symbol)?$base[1]->symbol:$base[0]->symbol];
        return $base_coins;
    }

    public function create()
    {
        $products = Product::where('status','Activo')->orderBy('name')->get();
        $suppliers = Supplier::where('status','Activo')->orderBy('name')->get();
        $base_coins = $this->loadcoinbase();
        if ($base_coins == '')
            return view('purchases.index',compact('purchases','message'));

        return view('purchases.create',compact('suppliers','products','base_coins'));
    }

    public function store(StorePurchaseRequest $request)
    {
        DB::beginTransaction();
        try {
            $status = 'Cancelada';
            $paid_mount = $request->mount;

            $supplier = Supplier::find($request->supplier_id);
            $last_balance = $supplier->balance;

            if($request->conditions == "Credito") {
                if ($request->rate_exchange <> 1) {
                    $paid_mount = $request->mount / $request->rate_exchange;
                }
                $balance_supplier = $supplier->balance + $paid_mount;
                $supplier->balance = $balance_supplier;
                $supplier->save();
                $status = 'Pendiente';
                $paid_mount = 0;
                if ($last_balance < 0) {   // proveedor con saldo negativo antes de la compra
                    $balance = $last_balance + $request->mount;
                    $status = ($balance <= 0 ? 'Cancelada' : 'Parcial');
                    $paid_mount = ($balance <= 0 ? $request->mount : -1 * $last_balance);
                }
            }
            $purchase = Purchase::create($request->all());
            if ($supplier->balance == 0) {
                Purchase::where('supplier_id',$supplier->id)->update([
                    'status' => 'Historico',
                ]);
                PaymentSupplier::where('supplier_id',$supplier->id)->update([
                    'status' => 'Historico'
                ]);
            }
            else {
              $purchase->status = $status;
              $purchase->paid_mount = $paid_mount;
              $purchase->save();
            }
            $item = 1;
            foreach ($request->product_id as $key => $value) {
                $results[] = array("product_id"=>$request->product_id[$key], "tax_id"=>$request->tax_id[$key],
                     "item"=>$item,"quantity"=>$request->quantity[$key], "price"=>$request->price[$key],
                     "tax"=>$request->tax[$key]);
                $product = Product::find($request->product_id[$key]);
                //La linea de abajo mantiene la situacion de leer el factor de la moneda de calculo con la monea de la orden
                $product->cost_price = ($request->coin_id == 1 ? $request->price[$key] / $request->rate_exchange : $request->price[$key]) ;
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
        return redirect()->route('purchases.index')->with('status',$message);

    }

    public function show($id)
    {
        $suppliers = Supplier::where('status','Activo')->get();
        $coins = Coin::where('status','Activo')->get();
        $base_coins = $this->loadcoinbase();
        $purchase = Purchase::select('purchases.*','suppliers.name as proveedor','coins.name as moneda','coins.symbol as simbolo')
                ->join('suppliers','purchases.supplier_id','suppliers.id')
                ->join('coins','purchases.coin_id','coins.id')
                ->where('purchases.id',$id)->first();
        $purchase_details = PurchaseDetail::select('purchase_details.*','products.name')
            ->join('products','purchase_details.product_id','products.id')
            ->where('purchase_details.purchase_id',$id)
            ->where('purchase_details.status','Activo')->orderBy('item')->get();

        return view('purchases.show',compact('purchase','purchase_details','suppliers','coins','base_coins'));
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
