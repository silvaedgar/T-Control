<?php

namespace App\Http\Controllers;

use App\Models\PaymentSupplier;
use App\Models\PaymentForm;
use App\Models\Purchase;
use App\Models\Coin;
use App\Models\Supplier;
use \Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;
use App\Traits\FiltersTrait;
use App\Traits\GetDataCommonTrait;
use App\Traits\CalculateMountsTrait;

use App\Http\Requests\StorePaymentSupplierRequest;
use App\Http\Requests\UpdatePaymentSupplierRequest;

class PaymentSupplierController extends Controller
{
    use FiltersTrait, GetDataCommonTrait, CalculateMountsTrait;

    public function __construct() {
        $this->middleware('role');
    }

    public function load_payments($filter=[]) {
        if (count($filter) == 0)
            return PaymentSupplier::select('payment_suppliers.*','payment_suppliers.payment_date as date')
                ->orderBy('payment_date','desc')->orderBy('created_at','desc');
        else
            return  PaymentSupplier::select('payment_suppliers.*','payment_suppliers.payment_date as date')
                ->where($filter)->orderBy('payment_date','desc')->orderBy('created_at','desc');
    }

    public function filter(Request $request) {
        $filter = $this->create_filter($request,'payment_date');
        $paymentsuppliers = $this->load_payments($filter)->get();
        $data_common = ['header' => 'Pagos Realizados a Proveedores','buttons' =>[['message' => 'Reporte',
            'icon' => 'print','url' => route('paymentsuppliers.filter')], ['message' => 'Generar Pago a Proveedor',
            'icon' => 'person_add','url' => route('paymentsuppliers.create')]],
            'links' => [['message' => 'Crear Factura', 'url' =>route('purchases.create')]],
            'data_filter' => $this->data_filter($filter),
            'controller' => 'PaymentSupplier'];
        if ($request->option == "Report") {
            $pdf = PDF::loadView('shared.payment-list-report',['models' => $paymentsuppliers,'data_common'=> $data_common]);
            return $pdf->stream();
        }
        else
            return view('payment-suppliers.index',compact('paymentsuppliers','data_common'));
    }

    public function index()
    {
        $paymentsuppliers = $this->load_payments([])->get();
        $data_common = ['header' => 'Pagos Realizados a Proveedores','buttons' =>[['message' => 'Reporte',
            'icon' => 'print','url' => route('paymentsuppliers.filter')], ['message' => 'Generar Pago a Proveedor',
            'icon' => 'person_add','url' => route('paymentsuppliers.create')]],
            'links' => [['message' => 'Crear Factura', 'url' =>route('purchases.create')]],
            'data_filter' => $this->data_filter([]),
            'controller' => 'PaymentSupplier'];
        return view('payment-suppliers.index',compact('paymentsuppliers','data_common'));
    }

    /// Funcion que lee las monedas disponible pata facturas de compras segun la relacion existente
    public function loadbasecoins() {
        $base_coins = Coin::GetBaseCoins('calc_currency_purchase')->first();
        if ($base_coins == '')  // no hay monedas base
        {   $message = 'Error_No existe moneda base de compra. Verifique la configuración o consulte con el administrador';
            $paymentsuppliers = PaymentSupplier::orderBy('payment_date','desc')->orderBy('created_at','desc')->get();
            return view('payment-suppliers.index',compact('paymentsuppliers','message'));
        }
        return $base_coins;

    }
    public function create()
    {
        $payment_forms = PaymentForm::where('status','Activo')->get();
        $suppliers = Supplier::GetSuppliers()->get();
        $pending_purchases = Purchase::where('status','Parcial')->orwhere('status','Pendiente')
            ->orderBy('supplier_id')->get();

        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
        $rate = $this->get_base_coin_rate($calc_coin->id);
        $coins = $this->get_coins_invoice_payment($rate,'calc_currency_sale')->get();
        $rate = $rate->first();
        // return response()->json([
        //     'base_coin' => $base_coin,
        //     'calc_coin' => $calc_coin,
        //     'rate' => $rate,
        //     'coins' => $coins
        // ], 200);
        $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
            'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol, 'rate' => $rate->purchase_price,
            'controller' => 'PaymentSupplier', 'header' => 'Pago a Proveedor',
            'sub_header' => "Moneda de Calculo: ".$calc_coin->symbol.' - Tasa :'.number_format($rate->purchase_price,2),
            'message_title' => '', 'message_subtitle' => '',
            'links_header' => ['message' => 'Atras','url' => url()->previous()],
            'links_create' => [['message' => 'Crear Factura', 'url' => route('purchases.create')]],'cols'=> 3];

        return view('payment-suppliers.create',compact('suppliers','payment_forms','pending_purchases','coins','data_common'));
    }

    private  function calc_balance_supplier(StorePaymentSupplierRequest $request)
    {
        if ($request->coin_id == $request->calc_currency ) { // moneda de pago es moneda de calculo
            return ($request->mount);
        }
        return  ($request->mount / $request->rate_exchange);  //Aqui va el factor
    }

    private function calc_balance_purchase($mount, $balance) {
        if ($mount >= $balance) {
            return  ['paid_mount' => $balance,'status' => 'Cancelada','mount'=> $mount - $balance];
        }
        return  ['paid_mount' => $mount,'status' => 'Parcial','mount'=> 0];
    }

    private function verify_data_purchase(StorePaymentSupplierRequest $request, Purchase $purchase)
    {
        $paymentcurrency = $request->coin_id;
        $balance_purchase = $purchase->mount - $purchase->paid_mount;
        if ($paymentcurrency == $purchase->coin_id){ //Moneda de Pago es igual a la FC
            return $this->calc_balance_purchase($request->mount,$balance_purchase);
        }
        if ($paymentcurrency == $request->calc_currency) {
            $mount_payment_currency = $request->mount * $request->rate_exchange;
            return  $this->calc_balance_purchase($mount_payment_currency,$balance_purchase);
        }
        $mount_payment_calc = $request->mount / $request->rate_exchange;
        return  $this->calc_balance_purchase($mount_payment_calc,$balance_purchase);
    }

    private function update_supplier(StorePaymentSupplierRequest $request,Supplier $supplier) {
        $balance_supplier = $supplier->balance - $this->calc_balance_supplier($request);
        $supplier->balance = $balance_supplier;
        $supplier->save();
        if ($balance_supplier == 0) {
            Purchase::where('supplier_id',$supplier->id)->update([
                'status' => 'Historico'
            ]);
            PaymentSupplier::where('supplier_id',$supplier->id)->update([
                'status' => 'Historico'
            ]);
        }
    }

    public function store(StorePaymentSupplierRequest $request)
    {
        $continue = true;
        $exist_payment = PaymentSupplier::where('supplier_id',$request->supplier_id)->where('mount',$request->mount)
            ->where('payment_date',$request->payment_date)->first();
        if ($exist_payment != '') {
            $fecha1 = new Carbon($exist_payment->created_at);
            if ($fecha1->diffInMinutes(now()) < 40)
                $continue = false;
        }
        if ($continue) {
            DB::beginTransaction();
            try {
                // la tasa hay que actualizar para los calculos posteriores o simplemente no mostrar
                // en paymentclient si va obligado
                // $rate_exchange = ($request->rate_exchange == 1 ? $request->rate_date : $request->rate_exchange);
                $paymentsupplier = PaymentSupplier::create($request->all());
                $supplier = Supplier::find($request->supplier_id);
                $purchase_pendings =  Purchase::where('supplier_id',$request->supplier_id)
                    ->whereIn('status',['Pendiente','Parcial'])->orderBy('purchase_date')->get();
                // La consulta anterior tambien puede hacerse de esta forma
                // $purchase_pendings =  Purchase::where('supplier_id',$request->supplier_id)
                //     ->where(function($query) { $query->where('status','Pendiente')
                //         ->orwhere('status','Parcial');
                //     })->orderBy('purchase_date')->get();
                $purchase_update = [];
                $monto = $request->mount;
                foreach ($purchase_pendings as $key => $value) {
                    if ($monto > 0) {
                        array_push($purchase_update,$this->verify_data_purchase($request,$purchase_pendings[$key]));
                        $purchase_pendings[$key]->paid_mount = $purchase_pendings[$key]->paid_mount + $purchase_update[$key]['paid_mount'];
                        $purchase_pendings[$key]->status = $purchase_update[$key]['status'];
                        $purchase_pendings[$key]->save();
                        $monto = $purchase_update[$key]['mount'];
                    }
                }
                $this->update_supplier($request,$supplier);
                $message = "Ok_Pago a Proveedor $supplier->name  procesado con exito. Se actualizaron Balance y Facturas de Compra";
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
                echo $th;
                $message =  'Error_Error generando Pago a Proveedor verifique';
            }
        }
        else {
            return back()->with('status','Error_Registro ya existente espere 5 minuto para intentar nuevamente');
        }
        // echo $message;
        return redirect()->route('paymentsuppliers.index')->with('status',$message);
    }

    public function show($id)
    {

        $payment_forms = PaymentForm::where('status','Activo')->get();
        $payment = PaymentSupplier::select('payment_suppliers.*','suppliers.name as supplier','coins.name as moneda','coins.symbol as simbolo')
                ->join('suppliers','payment_suppliers.supplier_id','suppliers.id')
                ->join('coins','payment_suppliers.coin_id','coins.id')
                ->where('payment_suppliers.id',$id)->first();
        $suppliers = Supplier::where('status','Activo')->where('id',$payment->supplier_id)->get();

        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
        $rate = $this->get_base_coin_rate($calc_coin->id);
        $coins = $this->get_coins_invoice_payment($rate,'calc_currency_sale')->get();
        $rate = $rate->first();
        $mount_other = '';
        if ($payment->coin_id != $calc_coin->id)
            $mount_other = $this->mount_other($payment,$calc_coin,$base_coin);
        $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
            'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol, 'rate' => $rate->sale_price,
            'controller' => 'PaymentSupplier', 'header' => 'Pago a Proveedores',
            'sub_header' => "Pagado en ".$payment->simbolo.($payment->rate_exchange > 1 ? 
                            ' - Tasa :'.number_format($payment->rate_exchange,2) : ''),
            'message_title' => 'Saldo: '.$suppliers[0]->balance.' '.$calc_coin->symbol,
            'message_subtitle' => $mount_other != '' ? "Monto en ".$mount_other : '', 'cols' => 3,
            'links_header' => ['message' => 'Atras','url' => url()->previous()],
            'links_create' => [['message' => 'Crear Factura', 'url' =>route('purchases.create')],
                    ['message' => 'Generar Pago', 'url' =>route('paymentsuppliers.create')]]];

        // $mount_other = $payment->simbolo == 'BsD' ? '$ '.number_format($payment->mount / $payment->rate_exchange,2)  :
        //                             "BsD ".number_format($payment->mount * $payment->rate_exchange,2);
        return view('payment-suppliers.show',compact('payment','suppliers','coins','data_common','payment_forms'));
    }

    public function edit(PaymentSupplier $paymentSupplier)
    {
        //
    }

    public function update(UpdatePaymentSupplierRequest $request, PaymentSupplier $paymentSupplier)
    {
        //
    }

    public function destroy(PaymentSupplier $paymentSupplier)
    {
        return "AJUSTAR SALDO PAGO PROVEEDOR";
    }
}
