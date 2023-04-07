<?php

namespace App\Traits;

use App\Models\Coin;
use App\Http\Requests\StoreCoinRequest;
use App\Traits\SharedTrait;

trait CoinTrait
{
    use SharedTrait;

    public function getCoins($filter = [])
    {
        return Coin::where($filter)->orderby('name', 'asc');
    }

    public function saveCoin(StoreCoinRequest $request)
    {
        $response = $this->saveModel('Coin', $request);
        if ($response['success']) {
            $response['message'] = "Moneda $request->name " . ($request->id == 0 ? 'creada' : 'actualizada') . ' con exito';
        }
        return redirect()
            ->route('maintenance.coins.index')
            ->with('message_status', $response['message']);
    }

    // Obtiene la moneda base segun la operacion compra venta y la de base(es BsD, cambia por pais o conversion monetaria)
    public function getBaseCoin($field)
    {
        return Coin::GetBaseCoins($field);
    }

    public function getBaseCoinRate($id)
    {
        return Coin::GetCurrencyCalcValue($id, 'currency_values.base_currency_id', 'currency_values.coin_id')->union(Coin::GetCurrencyCalcValue($id, 'currency_values.coin_id', 'currency_values.base_currency_id'));
    }

    public function getCoinsInvoicePayment($rate, $field)
    {
        return $rate->union(Coin::GetBaseCoins($field))->orderBy('name');
    }

    public function generateDataCoin($coin_calc)
    {
        $base_coin = $this->getBaseCoin('base_currency')->first();
        $calc_coin = $this->getBaseCoin($coin_calc)->first();
        $rate = $this->getBaseCoinRate($calc_coin->id)->first();
        return ['base_coin' => $base_coin, 'calc_coin' => $calc_coin, 'rate' => $coin_calc == 'calc_currency_sale' ? $rate->sale_price : $rate->purchase_price];
    }

    public function loadCoinType($coinCalc, $field, $config, $isPayment = true)
    {
        $data = $this->generateDataCoin('calc_currency_purchase');
        $config['data']['calcCoin'] = $data['calc_coin'];
        $config['data']['baseCoin'] = $data['base_coin'];
        $config['data']['calcCoin']->$field = $data['rate']; // no se porque dos veces
        $config['data']['calcCoin']->rate = $data['rate']; // no se porque dos veces

        $config['data']['coins'] = $this->getCoinsInvoicePayment($this->getBaseCoinRate($data['calc_coin']->id), 'calc_currency_purchase')->get();
        $config['header']['subTitle'] = 'Moneda de Calculo ' . $config['data']['calcCoin']->symbol;

        if (!$isPayment) {
            if ($config['data']['calcCoin']->id != $config['data']['baseCoin']->id) {
                $config['header']['subTitle'] .= ' -- Tasa ' . $data['rate'] . $config['data']['baseCoin']->symbol;
            }
        }
        return $config;
    }
}
