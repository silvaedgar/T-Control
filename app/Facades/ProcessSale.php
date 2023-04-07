<?php

namespace App\Facades;

use Illuminate\Support\Facades\DB;

use App\Models\Sale;
use App\Models\Client;
use App\Models\PaymentClient;
use App\Models\Product;

use App\Http\Requests\StoreSaleRequest;

use App\Traits\CoinTrait;
use App\Traits\CalculateMountsTrait;

class ProcessSale
{
    use CoinTrait, CalculateMountsTrait;

    public function getData($id = 0)
    {
        $data = $this->generateDataCoin('calc_currency_sale');
        if ($id > 0) {
            $data['invoice'] = Sale::with('Client', 'Coin', 'SaleDetails', 'SaleDetails.Product')
                ->where('id', $id)
                ->first();
            $data['mount_other'] = $data['calc_coin']->id != $data['base_coin']->coin_id ? $this->mount_other($data['invoice'], $data['calc_coin']) : 0;
        }
        $data['coins'] = $id > 0 ? [$data['invoice']->coin] : $this->getCoinsInvoicePayment($this->getBaseCoinRate($data['calc_coin']->id), 'calc_currency_sale')->get();
        $data['header'] = 'Detalle Factura de Venta';
        $data['clients'] = $id > 0 ? [$data['invoice']->client] : Client::GetClients()->get();
        $data['products'] = Product::GetProducts()->get();

        return $data;
    }

    public function storeSale(StoreSaleRequest $request)
    {
        DB::beginTransaction();
        try {
            $data_coin = $this->generateDataCoin('calc_currency_sale');
            // $base_coin = $this->get_base_coin('calc_currency_sale')->first();
            $rate_exchange = $request->rate_exchange;
            if ($request->rate_exchange == 1) {
                // la taza es automatica cuando la Factura es la moneda de calculo
                $rate_exchange = $request->rate_exchange_date;
            }
            $sale = Sale::create($request->all());
            $sale->update(['rate_exchange' => $rate_exchange]); // Actualiza la tasa en caso de ser 1
            $status = 'Cancelada';
            $mount = $request->mount;
            $client = Client::find($request->client_id);
            $last_balance = $client->balance;
            $balance_client = $client->balance;
            if ($request->conditions == 'Credito') {
                $status = 'Pendiente';
                if ($client->count_in_bs == 'S') {
                    $balance_client += $request->coin_id == $data_coin['base_coin']->id ? $mount : $mount * $rate_exchange;
                } else {
                    $balance_client += $request->coin_id == $data_coin['calc_coin']->id ? $mount : $mount / $rate_exchange;
                }
                $client->balance = $balance_client;
                $client->save();
                if ($last_balance < 0) {
                    // cliente con saldo a favor antes de la venta
                    $status = $balance_client <= 0 ? 'Cancelada' : 'Parcial';
                    $mount = $balance_client <= 0 ? $request->mount : -1 * $last_balance;
                }
                if ($balance_client == 0) {
                    $status = 'Historico';
                    PaymentClient::where('client_id', $client->id)->update([
                        'status' => 'Historico',
                    ]);
                }
                $sale->update([
                    'status' => $status,
                    'paid_mount' => $mount,
                ]); // Actualiza la tasa en caso de ser 1
            }
            $item = 1;
            foreach ($request->product_id as $key => $value) {
                $results[] = ['product_id' => $request->product_id[$key], 'tax_id' => $request->tax_id[$key], 'item' => $item, 'quantity' => $request->quantity[$key], 'price' => $request->price[$key], 'tax' => $request->tax[$key]];
                $item++;
            }
            $sale->SaleDetails()->createMany($results);

            $message = 'Factura de Venta de ' . $client->names . ' por un Monto de: ' . number_format($mount, 2) . ' ' . $sale->coin->symbol . ' .CREADA CON EXITO';
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            // echo $th;
            $message = 'No se genero Factura de Venta. Consulte con el Administrador del Sistema. Error: ' . $th->getMessage();
        }
        return $message;
    }
}
