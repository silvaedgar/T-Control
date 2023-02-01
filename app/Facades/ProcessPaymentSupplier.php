<?php

namespace App\Facades;

use \Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


use App\Models\PaymentSupplier;
use App\Models\PaymentForm;
use App\Models\Purchase;
use App\Models\Coin;
use App\Models\Supplier;

use App\Http\Requests\StorePaymentSupplierRequest;
use App\Http\Requests\UpdatePaymentSupplierRequest;
use App\Traits\GetDataCommonTrait;
use App\Traits\CalculateMountsTrait;

class ProcessPaymentSupplier {

    use GetDataCommonTrait, CalculateMountsTrait;

    private function calc_balance_supplier(StorePaymentSupplierRequest $request,$rate_exchange)
    {
        $data_coin = $this->generate_data_coin('calc_currency_purchase');
        if ($data_coin['calc_coin']->id == $request->coin_id )  // moneda de pago es moneda de calculo
            return ($request->mount);
        if ($request->calc_currency_id == $data_coin['base_coin']->id) // la moneda de calculo es Bs pago en $ u otra
            $mount = $request->mount * $rate_exchange;
        else
            $mount =  ($request->mount / $rate_exchange);
        return $mount;
    }

    private function calc_mount_pending(PaymentSupplier $request,$mount_pending, $convert_mount_payment) {

        switch ($convert_mount_payment) {
            case '*':
                return $mount_pending * $request->rate_exchange;
            case '/':
                return $mount_pending / $request->rate_exchange;
            default:
                return $mount_pending;
        }
    }

    private function calc_balance_purchase(PaymentSupplier $request,Purchase $purchase,$mount_pending, $convert_mount_payment) {
        $balance_purchase = $purchase->mount - $purchase->paid_mount;
        if ($mount_pending >= $balance_purchase) {
            return  ['paid_mount' => $balance_purchase,'status' => 'Cancelada', 'opcion' => $convert_mount_payment,
                        'mount_pending' => $this->calc_mount_pending($request,$mount_pending - $balance_purchase, $convert_mount_payment)];
        }
        return  ['paid_mount' => $mount_pending,'status' => 'Parcial','mount_pending' => 0,
                'opcion' => $convert_mount_payment];
    }

    private function verify_data_purchase(PaymentSupplier $payment_supplier,Purchase $purchase,$calc_coin_current_id, $mount_pending)
    {

        $payment_currency = $payment_supplier->coin_id;
        $balance_purchase = $purchase->mount - $purchase->paid_mount;
        if ($payment_currency == $purchase->coin_id){ //Moneda de Pago es igual a la FC
            return $this->calc_balance_purchase($payment_supplier,$purchase,$mount_pending,"=");
        }
        if ($payment_currency == $calc_coin_current_id) {
            $mount_payment_currency = $mount_pending * $payment_supplier->rate_exchange;
            return  $this->calc_balance_purchase($payment_supplier,$purchase,$mount_payment_currency,"/");
        }
        $mount_payment_currency = $mount_pending / $payment_supplier->rate_exchange;
        return  $this->calc_balance_purchase($payment_supplier,$purchase,$mount_payment_currency,"*");
    }

    private function update_supplier(StorePaymentSupplierRequest $request,Supplier $supplier,$rate_exchange) {

        $balance_supplier = $supplier->balance - $this->calc_balance_supplier($request,$rate_exchange);
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

    public function getDataPayment($id=0) {

        $data= $this->generate_data_coin('calc_currency_purchase');
        if ($id > 0) {
            $data['invoice'] = PaymentSupplier::with('Supplier','Coin','PaymentForm')->where('id',$id)->first();
            $data['mount_other'] = ($data['calc_coin']->id != $data['base_coin']->coin_id ? $this->mount_other($data['invoice'],$data['calc_coin']) : 0);
        }
        $data['suppliers'] = $id > 0 ? array($data['invoice']->supplier) : Supplier::GetSuppliers()->get();
        $data['payment_forms'] = $id > 0 ? array($data['invoice']->PaymentForm) : PaymentForm::where('status','Activo')->orderBy('payment_form')->get();
        $data['coins'] = $id > 0 ? array($data['invoice']->coin) : $this->get_coins_invoice_payment($this->get_base_coin_rate($data['calc_coin']->id),'calc_currency_purchase')->get();
        $data['header'] = $id > 0 ? "Pago a Proveedor" : "Crear Pago a Proveedor";

        return $data;
    }

    public function storePayment(StorePaymentSupplierRequest $request) {

        DB::beginTransaction();
        try {
            $supplier = Supplier::find($request->supplier_id);
            $rate_exchange = ($request->rate_exchange == 1 ? $request->rate_date : $request->rate_exchange);
            $payment_supplier = PaymentSupplier::create($request->all());
            $payment_supplier->update(["rate_exchange" => $rate_exchange]); // Actualiza la tasa en caso de ser 1
            $purchase_pendings =  Purchase::where('supplier_id',$request->supplier_id)
                ->whereIn('status',['Pendiente','Parcial'])->orderBy('purchase_date')->get();
            $purchase_update = [];
            $monto = $request->mount;
            foreach ($purchase_pendings as $key => $value) {
                    if ($monto > 0) {
                        array_push($purchase_update,$this->verify_data_purchase($payment_supplier,
                                $purchase_pendings[$key],$request->calc_currency_id,$monto));
                        $purchase_pendings[$key]->paid_mount = $purchase_pendings[$key]->paid_mount + $purchase_update[$key]['paid_mount'];
                        $purchase_pendings[$key]->status = $purchase_update[$key]['status'];
                        $purchase_pendings[$key]->save();
                        $monto = $purchase_update[$key]['mount_pending'];
                    }
            }
            $this->update_supplier($request,$supplier,$rate_exchange);
            $message = 'Pago al Proveedor Cliente '.$supplier->name." por un Monto de: ".number_format($request->mount,2)." ".$payment_supplier->coin->symbol." procesado con exito. Se actualizaron Balance y Facturas de Compra";
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            // echo $th;
            $message =  "No se proceso Pago al Proveedor: $supplier->name por un Monto de: ".number_format($request->mount,2)." "
                ." Consulte con el Administrador del Sistema. Error: ".$th->getMessage();
        }
        return $message;
    }
}
