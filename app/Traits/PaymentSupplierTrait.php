<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;

// use App\Models\Product;
// use App\Models\ProductCategory;
use App\Models\PaymentSupplier;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Tax;

use App\Http\Requests\PaymentSupplierRequest;

use App\Traits\CoinTrait;

trait PaymentSupplierTrait
{
    use CoinTrait;

    public function storePayment(PaymentSupplierRequest $request)
    {
        $supplier = Supplier::find($request->supplier_id);
        DB::beginTransaction();
        try {
            $rateExchange = $request->rate_exchange == 1 ? $request->rate_exchange_date : $request->rate_exchange;
            $paymentSupplier = PaymentSupplier::create($request->all());
            $paymentSupplier->update(['rate_exchange' => $rateExchange]); // Actualiza la tasa en caso de ser 1
            $purchasePendings = Purchase::where('supplier_id', $request->supplier_id)
                ->whereIn('status', ['Pendiente', 'Parcial'])
                ->orderBy('purchase_date')
                ->get();
            $purchaseUpdate = [];
            $monto = $request->mount;
            foreach ($purchasePendings as $key => $value) {
                if ($monto > 0) {
                    array_push($purchaseUpdate, $this->verifyDataPurchase($paymentSupplier, $purchasePendings[$key], $request->calc_currency_id, $monto));
                    $purchasePendings[$key]->paid_mount = $purchasePendings[$key]->paid_mount + $purchaseUpdate[$key]['paid_mount'];
                    $purchasePendings[$key]->status = $purchaseUpdate[$key]['status'];
                    $purchasePendings[$key]->save();
                    $monto = $purchaseUpdate[$key]['mount_pending'];
                }
            }
            $this->updateSupplier($request, $supplier, $rateExchange);
            $message = 'Pago al Proveedor Cliente ' . $supplier->name . ' por un Monto de: ' . number_format($request->mount, 2) . ' ' . $paymentSupplier->coin->symbol . ' procesado con exito. Se actualizaron Balance y Facturas de Compra';
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            // echo $th;
            $message = "No se proceso Pago al Proveedor: $supplier->name por un Monto de: " . number_format($request->mount, 2) . ' ' . ' Consulte con el Administrador del Sistema. Error: ' . $th->getMessage();
        }
        return $message;
    }

    private function calcMountPending(PaymentSupplier $request, $mount_pending, $convert_mount_payment)
    {
        switch ($convert_mount_payment) {
            case '*':
                return $mount_pending * $request->rate_exchange;
            case '/':
                return $mount_pending / $request->rate_exchange;
            default:
                return $mount_pending;
        }
    }

    private function calcBalancePurchase(PaymentSupplier $request, Purchase $purchase, $mount_pending, $convert_mount_payment)
    {
        $balance_purchase = $purchase->mount - $purchase->paid_mount;
        if ($mount_pending >= $balance_purchase) {
            return ['paid_mount' => $balance_purchase, 'status' => 'Cancelada', 'opcion' => $convert_mount_payment, 'mount_pending' => $this->calcMountPending($request, $mount_pending - $balance_purchase, $convert_mount_payment)];
        }
        return ['paid_mount' => $mount_pending, 'status' => 'Parcial', 'mount_pending' => 0, 'opcion' => $convert_mount_payment];
    }

    private function verifyDataPurchase(PaymentSupplier $paymentSupplier, Purchase $purchase, $calc_coin_current_id, $mount_pending)
    {
        $payment_currency = $paymentSupplier->coin_id;
        $balance_purchase = $purchase->mount - $purchase->paid_mount;
        if ($payment_currency == $purchase->coin_id) {
            //Moneda de Pago es igual a la FC
            return $this->calcBalancePurchase($paymentSupplier, $purchase, $mount_pending, '=');
        }
        if ($payment_currency == $calc_coin_current_id) {
            $mount_payment_currency = $mount_pending * $paymentSupplier->rate_exchange;
            return $this->calcBalancePurchase($paymentSupplier, $purchase, $mount_payment_currency, '/');
        }
        $mount_payment_currency = $mount_pending / $paymentSupplier->rate_exchange;
        return $this->calcBalancePurchase($paymentSupplier, $purchase, $mount_payment_currency, '*');
    }

    private function updateSupplier(PaymentSupplierRequest $request, Supplier $supplier, $rateExchange)
    {
        $balance_supplier = $supplier->balance - $this->calcBalanceSupplier($request, $rateExchange);
        $supplier->balance = $balance_supplier;
        $supplier->save();
        if ($balance_supplier == 0) {
            Purchase::where('supplier_id', $supplier->id)
                ->where('status', '<>', 'Anulada')
                ->update(['status' => 'Historico']);
            PaymentSupplier::where('supplier_id', $supplier->id)
                ->where('status', '<>', 'Anulado')
                ->update(['status' => 'Historico']);
        }
    }

    private function calcBalanceSupplier(PaymentSupplierRequest $request, $rate_exchange)
    {
        $data_coin = $this->generateDataCoin('calc_currency_purchase');
        if ($data_coin['calc_coin']->id == $request->coin_id) {
            // moneda de pago es moneda de calculo
            return $request->mount;
        }
        if ($request->calc_currency_id == $data_coin['base_coin']->id) {
            // la moneda de calculo es Bs pago en $ u otra
            $mount = $request->mount * $rate_exchange;
        } else {
            $mount = $request->mount / $rate_exchange;
        }
        return $mount;
    }
}
