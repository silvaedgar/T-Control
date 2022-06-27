<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PaymentSupplier;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Coin;
use \Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\FiltersTrait;
use App\Traits\GetDataCommonTrait;
use App\Traits\CalculateMountsTrait;


use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;

use PDF;

class PurchaseController extends Controller
{
    use  FiltersTrait, GetDataCommonTrait, CalculateMountsTrait;

    public function __construct() {
        $this->middleware('role');
    }

    // public function suppliers($id) {
    //     return  Supplier::select('purchases.*','suppliers.balance','coins.symbol')->leftjoin('purchases','suppliers.id','purchases.supplier_id')
    //         ->leftjoin('coins','purchases.coin_id','coins.id')->where('suppliers.id',$id)
    //         ->orderBy('name')->get();
    // }


    public function load_purchases($filter) {
        if (count($filter) ==0)
            return Purchase::select('purchases.*','purchases.purchase_date as date')->orderBy('purchase_date','desc')->orderBy('created_at','desc');
        else
            return Purchase::select('purchases.*','purchases.purchase_date as date')->where($filter)->orderBy('purchase_date','desc')->orderBy('created_at','desc');
    }

    public function filter(Request $request) {

        $filter = $this->create_filter($request,'purchase_date');
        $purchases = $this->load_purchases($filter)->get();
        $data_common = ['header' => 'Facturas de Compras','buttons' =>[['message' => 'Crear Factura de Compra',
            'icon' => 'person_add','url' => route('purchases.create')]],
            'links' => [['message' => 'Generar Pago a Proveedor', 'url' =>route('paymentsuppliers.create')]],
            'data_filter' => $this->data_filter($filter),
            'controller' => 'Purchase'];
        if ($request->option == "Report") {
            $pdf = PDF::loadView('shared.payment-list-report',['models' => $purchases,'data_common'=> $data_common]);
            return $pdf->stream();
        }
        else {
            return view('purchases.index',compact('purchases','data_common'));
        }
    }

    public function index()
    {
        $purchases = $this->load_purchases([])->get();
        $data_common = ['header' => 'Facturas de Compras','sub_header' => '',
        'message_title' => '', 'message_subtitle' =>'',
        'buttons' =>[['message' => 'Crear Factura de Compra', 'target' => false,
            'icon' => 'person_add','url' => route('purchases.create')]],
        'links_header' => ['message' => '', 'url' =>''],
        'links_create' => [['message' => 'Pago a Proveedor', 'url' => route('paymentsuppliers.create')],
                    ['message' => 'Venta', 'url' => route('sales.create')],
                    ['message' => 'Pago a Cliente', 'url' => route('paymentclients.create')]],
            'data_filter' => $this->data_filter([]), 'cols' => 2,
            'controller' => 'Purchase'];
        return view('purchases.index',compact('purchases','data_common'));
        //
    }

    public function create()
    {
        $products = Product::GetProducts()->get();
        $suppliers = Supplier::GetSuppliers()->get();


        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin = $this->get_base_coin('calc_currency_purchase')->first();
        $rate = $this->get_base_coin_rate($calc_coin->id);
        $coins = $this->get_coins_invoice_payment($rate,'calc_currency_purchase')->get();
        $rate = $rate->first();
        $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
            'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol,
            'rate' => $rate->purchase_price,
            'controller' => 'Purchase', 'header' => 'Crear Factura de Compra',
            'sub_header' => "Moneda de Calculo: ".$calc_coin->symbol.' - Tasa :'.number_format($rate->purchase_price,2),
            'message_title' => '0.00 '.$calc_coin->symbol,  'cols'=>'3',
            'message_subtitle' => ($calc_coin->symbol != $base_coin->symbol ? '0.00 '.$base_coin->symbol : ''),
            'links_header' => ['message' => 'Atras','url' => url()->previous()],
            'links_create' => [['message' => 'Pago a Proveedor', 'url' => route('paymentsuppliers.create')],
                    ['message' => 'Venta', 'url' => route('sales.create')],
                    ['message' => 'Pago a Cliente', 'url' => route('paymentclients.create')]]];

        return view('purchases.create',compact('suppliers','products','coins','data_common'));
    }

    public function store(StorePurchaseRequest $request)
    {
        $continue = true;
        $exist_purchase = Purchase::where('supplier_id',$request->supplier_id)->where('mount',$request->mount)
            ->where('purchase_date',$request->purchase_date)->first();
        if ($exist_purchase != '') {
            $fecha1 = new Carbon($exist_purchase->created_at);
            if ($fecha1->diffInMinutes(now()) < 5)
                $continue = false;
        }
        if ($continue) {
            DB::beginTransaction();
            try {
                $rate_exchange = $request->rate_exchange;
                if ($rate_exchange == 1) {
                    // la taza es automatica cuando la Factura es la moneda de calculo
                    $rate_exchange = $request->rate_exchange_date;
                }
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
                else {
                    $status = "Historico";
                }
                $purchase = Purchase::create($request->all());
                if ($supplier->balance == 0) {
                    Purchase::where('supplier_id',$supplier->id)->update([
                        'status' => 'Historico',
                        'rate_exchange' => $rate_exchange,
                        'paid_mount' => $paid_mount
                    ]);
                    PaymentSupplier::where('supplier_id',$supplier->id)->update([
                        'status' => 'Historico'
                    ]);
                }
                else {
                    $purchase->status = $status;
                    $purchase->paid_mount = $paid_mount;
                    $purchase->rate_exchange = $rate_exchange;
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
        }
        else {
            return back()->with('status','Error_Registro ya existente espere 5 minuto para intentar nuevamente');
        }
        // echo $message;
        return redirect()->route('purchases.index')->with('status',$message);

    }

    public function show($id)
    {
        $invoice = Purchase::select('purchases.*','suppliers.name as proveedor','coins.name as moneda','coins.symbol as simbolo')
                ->join('suppliers','purchases.supplier_id','suppliers.id')
                ->join('coins','purchases.coin_id','coins.id')
                ->where('purchases.id',$id)->first();
        $invoice_details = PurchaseDetail::select('purchase_details.*','products.name')
            ->join('products','purchase_details.product_id','products.id')
            ->where('purchase_details.purchase_id',$id)
            ->where('purchase_details.status','Activo')->orderBy('item')->get();
        $suppliers = Supplier::GetSuppliers()->where('id',$invoice->supplier_id)->get();

        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin = $this->get_base_coin('calc_currency_purchase')->first();
        $rate = $this->get_base_coin_rate($calc_coin->id);
        $coins = $this->get_coins_invoice_payment($rate,'calc_currency_sale')->get();
        $rate = $rate->first();
        $mount_other = ($calc_coin->id != $base_coin->id ? $this->mount_other($invoice,$calc_coin,$base_coin) : '');
        $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
            'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol,
            'rate' => $rate->purchase_price,
            'controller' => 'Purchase', 'header' => 'Detalle Factura de Compra',
            'sub_header' => "Facturada en ".$invoice->simbolo.' - Tasa :'.number_format($invoice->rate_exchange,2),
            'message_title' => "Monto: ".$invoice->mount." ".$invoice->simbolo,
            'message_subtitle' => $mount_other != '' ? "Monto en ".$mount_other : '', 'cols'=> 3,
            'links_header' => ['message' => 'Atras','url' => url()->previous()],
            'links_create' => [['message' => 'Crear Factura', 'url' =>route('purchases.create')],
                    ['message' => 'Generar Pago a Proveedor', 'url' =>route('paymentsuppliers.create')]]];
        return view('purchases.show',compact('invoice','invoice_details','suppliers','coins','data_common'));
    }

    public function edit(Purchase $purchase)
    {
        //
    }

    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        //
    }
///// El destroy anula una factura
    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();
        $message="";
        // $purchase = Purchase::find($purchase->id);
        if ($purchase->conditions == "Credito") {
            $calc_coin = $this->get_base_coin('calc_currency_purchase')->first();
            if ($calc_coin_id == $purchase->coin_id)
                $mount = $purchase->mount;
            else {
                $rate = $this->get_base_coin_rate($calc_coin->id)->first();
                $base_coin = $this->get_base_coin('base_currency')->first();
                $mount = ($base_coin->id == $purchase->coin_id ? $purchase->mount * $rate->purchase_price : $purchase->mount / $rate->purchase_price );
            }
            $supplier = Supplier::find($purchase->supplier_id);
            Supplier::where('id',$purchase->supplier_id)->update(['balance' => $supplier->balance - $mount]);
            $message = "Actualizado Balance de Proveedor";
        }
        // Purchase::where('id',$purchase->id)->update(['status' => 'Anulada']);

        // DB::commit();
        // return redirect()->route('purchases.index')->with('status',"Ok_Factura de Compra Anulada. ");
        //
    }
}
