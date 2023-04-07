<?php

namespace App\Traits;

use App\Models\Sale;
use App\Models\Client;
use App\Models\PaymentClient;

use App\Http\Requests\SaleRequest;

use Illuminate\Support\Facades\DB;

use App\Traits\CoinTrait;

trait SaleTrait
{
    use CoinTrait;

    public function getSale($id)
    {
        return Sale::with('coin', 'client', 'saleDetails', 'saleDetails.product')->where('id', $id);
        // if (!is_array($filter)) {
        //     return Sale::with($with)->where('id', $id);
        // }
        // if (count($filter) == 0) {
        //     return Sale::orderBy('sale_date', 'desc')->orderBy('created_at', 'desc');
        // } else {
        //     return Sale::where($filter)
        //         ->orderBy('sale_date', 'desc')
        //         ->orderBy('created_at', 'desc');
        // }
    }

    public function saveSale(SaleRequest $request)
    {
        DB::beginTransaction();
        try {
            $data_coin = $this->generateDataCoin('calc_currency_sale');
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
