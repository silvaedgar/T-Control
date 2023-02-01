<?php

namespace App\Facades;

use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;

use App\Http\Requests\StorePurchaseRequest;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PaymentSupplier;

use App\Traits\GetDataCommonTrait;
use App\Traits\CalculateMountsTrait;

class ProcessPurchase {

    use GetDataCommonTrait, CalculateMountsTrait;

    public function getData($id=0) {

        $data = $this->generate_data_coin('calc_currency_purchase');
        if ($id > 0) {
            $data['invoice'] = Purchase::with('Supplier','Coin','PurchaseDetails','PurchaseDetails.Product')->where('id',$id)->first();
            $data['mount_other'] = ($data['calc_coin']->id != $data['base_coin']->coin_id ? $this->mount_other($data['invoice'],$data['calc_coin']) : 0);
        }
        $data['coins'] = $id > 0 ? array($data['invoice']->coin) : $this->get_coins_invoice_payment($this->get_base_coin_rate($data['calc_coin']->id),'calc_currency_purchase')->get();
        $data['header'] = 'Detalle Factura de Compra';
        $data['suppliers'] = $id > 0 ? array($data['invoice']->supplier) : Supplier::GetSuppliers()->get();
        $data['products'] = Product::GetProducts()->get();

        return $data;
    }

    public function storePurchase(StorePurchaseRequest $request) {

        DB::beginTransaction();
        try {
                $rate_exchange = $request->rate_exchange;
                if ($rate_exchange == 1) {
                // if ($request->coin_id == $request->calc_coin_id) {
                    // la tasa es automatica cuando la Factura es la moneda de calculo
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
                $purchase->PurchaseDetails()->createMany($results);
                $message = 'Factura de Compra de '.$supplier->name." por un Monto de: ".number_format($request->mount,2)." ".$purchase->coin->symbol." .CREADA CON EXITO";
                DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            $message =  'No se genero Factura de Compra. Consulte con el Administrador del Sistema. Error: '.$th->getMessage();
        }
        return $message;
    }
}
