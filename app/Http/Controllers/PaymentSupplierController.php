<?php

namespace App\Http\Controllers;

use App\Models\PaymentSupplier;
use App\Models\PaymentForm;
use App\Models\Purchase;
use App\Models\Coin;
use App\Models\Supplier;
use \Illuminate\Support\Facades\DB;


use App\Http\Requests\StorePaymentSupplierRequest;
use App\Http\Requests\UpdatePaymentSupplierRequest;

class PaymentSupplierController extends Controller
{
    public function index()
    {
        $paymentsuppliers = PaymentSupplier::orderBy('payment_date','desc')->orderBy('supplier_id','desc')->get();
        return view('payment-suppliers.index',compact('paymentsuppliers'));
        //
    }

    public function create()
    {
        $paymentforms = PaymentForm::where('status','Activo')->get();
        $suppliers = Supplier::where('status','Activo')->get();
        $base = Coin::where('calc_currency_purchase','S')->orwhere('base_currency','S')
                ->where('status','=','Activo')->orderBy('base_currency')->get();
        // if (count($base) > 2) {
        //     $message = 'Error_Verifique la Configuración de las Monedas. Consulte con el administrador';
        //     $purchases = Purchase::orderBy('id','desc')->get();
        //     return view('purchases.index',compact('purchases','message'));
        // }
        $base_coins = ['base_id' => $base[0]->id, 'base_name' => $base[0]->name,'base_calc_id'=>
                isset($base[1]->id)?$base[1]->id:$base[0]->id, 'base_calc_name'=> isset($base[1]->name)?$base[1]->name:$base[0]->name];
        $pendingpurchases = Purchase::where('status','Parcial')->orwhere('status','Pendiente')
            ->orderBy('supplier_id')->get();
        return view('payment-suppliers.create',compact('suppliers','paymentforms','base_coins','pendingpurchases'));
    }

    private  function calc_balance_supplier(StorePaymentSupplierRequest $request,$coin_base)
    {
        $paymentcurrency = $request->coin_id;
        if ($paymentcurrency == $coin_base ) { // moneda de pago es moneda de calculo
            return ($request->mount);
        }
        return  ($request->mount / $request->rate_exchange);
    }

    private function calc_balance_purchase($mount, $balance) {
        if ($mount >= $balance) {
            return  ['paid_mount' => $balance,'status' => 'Cancelada','mount'=> $mount - $balance];
        }
        return  ['paid_mount' => $mount,'status' => 'Parcial','mount'=> 0];
    }

    private function verify_data_purchase(StorePaymentSupplierRequest $request,Purchase $purchase,$currency_calc)
    {
        $paymentcurrency = $request->coin_id;
        $balance_purchase = $purchase->mount - $purchase->paid_mount;
        if ($paymentcurrency == $purchase->coin_id){ //Moneda de Pago es igual a la FC
            return $this->calc_balance_purchase($request->mount,$balance_purchase);
        }
        if ($paymentcurrency == $currency_calc) {
            $mount_payment_currency = $request->mount * $request->rate_exchange;
            return  $this->calc_balance_purchase($mount_payment_currency,$balance_purchase);
        }
        $mount_payment_calc = $request->mount / $request->rate_exchange;
        return  $this->calc_balance_purchase($mount_payment_calc,$balance_purchase);
    }

    private function update_supplier(StorePaymentSupplierRequest $request,Supplier $supplier,$coin_calc_id) {

        $balance_supplier = $supplier->balance - $this->calc_balance_supplier($request,$coin_calc_id);
        $supplier->balance = $balance_supplier;
        $supplier->save();
    }

    public function store(StorePaymentSupplierRequest $request)
    {
        DB::beginTransaction();
        try {
            $paymentsupplier = PaymentSupplier::create($request->all());
            $paymentcurrency = $request->coin_id;
            $supplier = Supplier::find($request->supplier_id);

            $base = Coin::where('calc_currency_sale','S')->orwhere('base_currency','S')
            ->where('status','Activo')->orderBy('base_currency')->get();


            $base = Coin::where('calc_currency_purchase','S')->orwhere('base_currency','S')
            ->where('status','Activo')->orderBy('base_currency')->get();

            $coin_calc_id = isset($base[1]->id)?$base[1]->id:$base[0]->id;

            $this->update_supplier($request,$supplier,$coin_calc_id);
            $purchase_pendings =  Purchase::where('supplier_id',$request->supplier_id)->where('status','Pendiente')
                ->orwhere('status','Parcial')->orderBy('purchase_date')->get();
            $purchase_update = [];
            $monto = $request->mount;
            foreach ($purchase_pendings as $key => $value) {
                if ($monto > 0) {
                    array_push($purchase_update,$this->verify_data_purchase($request,$purchase_pendings[$key],$coin_calc_id));
                    // dump($purchase_update);
                    $purchase_pendings[$key]->paid_mount = $purchase_pendings[$key]->paid_mount + $purchase_update[$key]['paid_mount'];
                    $purchase_pendings[$key]->status = $purchase_update[$key]['status'];
                    $purchase_pendings[$key]->save();

                    $monto = $purchase_update[$key]['mount'];
                }
            }
            $message = "Ok_Pago a Proveedor $supplier->name  procesado con exito. Se actualizaron Balance y Facturas de Compra";
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            echo $th;
            $message =  'Error_Error generando Pago a Proveedor verifique';
        }
        // echo $message;
        return redirect()->route('paymentsuppliers.index')->with('status',$message);
    }

    public function show(PaymentSupplier $paymentSupplier)
    {
        //
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
        //
    }
}
