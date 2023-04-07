<?php

namespace App\Facades;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\PaymentClient;
use App\Models\PaymentForm;
use App\Models\Sale;
use App\Models\Coin;
use App\Models\Client;

use App\Http\Requests\PaymentClientRequest;

use App\Traits\CoinTrait;
use App\Traits\CalculateMountsTrait;

class ProcessPaymentClient
{
    use CoinTrait, CalculateMountsTrait;

    private function calcBalanceClient(PaymentClientRequest $request, Client $client, $rate_exchange)
    {
        $paymentcurrency = $request->coin_id;
        if ($client->count_in_bs == 'S') {
            if ($request->coin_id != 1) {
                /// ojo tiene que ser calc_coin_id
                $mount = $request->mount * $rate_exchange;
            } else {
                $mount = $request->mount;
            }
            return $mount;
        }
        if ($request->calc_currency_id == $request->coin_id) {
            // moneda de pago es moneda de calculo
            return $request->mount;
        }
        if ($request->calc_currency_id == 1) {
            // la moneda de calculo es Bs pago en $ u otra
            $mount = $request->mount * $rate_exchange;
        } else {
            $mount = $request->mount / $rate_exchange;
        }
        return $mount;
    }

    private function calc_mount_pending(PaymentClient $request, $mount_pending, $convert_mount_payment)
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

    private function calc_balance_sale(PaymentClient $request, Sale $sale, $mount_pending, $convert_mount_payment)
    {
        $balance_sale = $sale->mount - $sale->paid_mount;
        if ($mount_pending >= $balance_sale) {
            return ['paid_mount' => $balance_sale, 'status' => 'Cancelada', 'opcion' => $convert_mount_payment, 'mount_pending' => $this->calc_mount_pending($request, $mount_pending - $balance_sale, $convert_mount_payment)];
        }
        return ['paid_mount' => $mount_pending, 'status' => 'Parcial', 'mount_pending' => 0, 'opcion' => $convert_mount_payment];
    }

    private function verify_data_sale(PaymentClient $payment_client, Sale $sale, $calc_coin_current_id, $mount_pending)
    {
        $payment_currency = $payment_client->coin_id;
        $balance_sale = $sale->mount - $sale->paid_mount;
        if ($payment_currency == $sale->coin_id) {
            //Moneda de Pago es igual a la FC
            return $this->calc_balance_sale($payment_client, $sale, $mount_pending, '=');
        }
        if ($payment_currency == $calc_coin_current_id) {
            $mount_payment_currency = $mount_pending * $payment_client->rate_exchange;
            return $this->calc_balance_sale($payment_client, $sale, $mount_payment_currency, '/');
        }
        $mount_payment_currency = $mount_pending / $payment_client->rate_exchange;
        return $this->calc_balance_sale($payment_client, $sale, $mount_payment_currency, '*');
    }

    private function updateModels(PaymentClientRequest $request, Client $client, $rate_exchange)
    {
        $balance_client = round($client->balance - $this->calcBalanceClient($request, $client, $rate_exchange), 2);
        $client->balance = $balance_client;
        $client->save();
        if ($balance_client == 0.0) {
            Sale::where('client_id', $client->id)->update([
                'status' => 'Historico',
            ]);
            PaymentClient::where('client_id', $client->id)->update([
                'status' => 'Historico',
            ]);
        }
    }

    public function getDataPayment($id = 0)
    {
        $data = $this->generateDataCoin('calc_currency_sale');

        if ($id > 0) {
            $data['invoice'] = PaymentClient::with('Client', 'Coin', 'PaymentForm')
                ->where('id', $id)
                ->first();
            $data['mount_other'] = $data['calc_coin']->id != $data['base_coin']->coin_id ? $this->mount_other($data['invoice'], $data['calc_coin']) : 0;
        }
        $data['clients'] = $id > 0 ? [$data['invoice']->client] : Client::GetClients()->get();
        $data['payment_forms'] =
            $id > 0
                ? [$data['invoice']->PaymentForm]
                : PaymentForm::where('activo', 1)
                    ->orderBy('payment_form')
                    ->get();
        $data['coins'] = $id > 0 ? [$data['invoice']->coin] : $this->getCoinsInvoicePayment($this->getBaseCoinRate($data['calc_coin']->id), 'calc_currency_sale')->get();
        $data['header'] = $id > 0 ? 'Pago de Cliente' : 'Crear Pago de Clientes';
        return $data;
    }

    public function storePayment(PaymentClientRequest $request)
    {
        $client = Client::find($request->client_id);
        DB::beginTransaction();
        try {
            $rate_exchange = $request->rate_exchange == 1 ? $request->rate_exchange_date : $request->rate_exchange;
            $payment_client = PaymentClient::create($request->all());
            $payment_client->update(['rate_exchange' => $rate_exchange]); // Actualiza la tasa en caso de ser 1
            $sale_pendings = Sale::where('client_id', $request->client_id)
                ->whereIn('status', ['Pendiente', 'Parcial'])
                ->orderBy('sale_date')
                ->get();
            $sale_update = [];
            $monto = $request->mount;
            foreach ($sale_pendings as $key => $value) {
                if ($monto > 0) {
                    array_push($sale_update, $this->verify_data_sale($payment_client, $sale_pendings[$key], $request->calc_currency_id, $monto));
                    $sale_pendings[$key]->paid_mount = $sale_pendings[$key]->paid_mount + $sale_update[$key]['paid_mount'];
                    $sale_pendings[$key]->status = $sale_update[$key]['status'];
                    $sale_pendings[$key]->save();
                    $monto = $sale_update[$key]['mount_pending'];
                }
            }
            $this->updateModels($request, $client, $rate_exchange);

            $message = 'Pago del Cliente ' . $client->names . ' por un Monto de: ' . number_format($request->mount, 2) . ' ' . $payment_client->coin->symbol . ' procesado con exito. Se actualizaron Balance y Facturas de Venta';
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            // echo $th;
            $message = "No se proceso Pago del Cliente: $client->names por un Monto de: " . number_format($request->mount, 2) . ' Consulte con el Administrador del Sistema. Error: ' . $th->getMessage();

            // $message =  "Error_Error generando Pago de cliente $client->names verifique";
        }
        return $message;
    }
}
