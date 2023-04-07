<?php

namespace App\Traits;
use App\Models\Coin;
use App\Models\Client;


trait GetDataCommonTrait {

    public function get_base_coin($field) {
        return Coin::GetBaseCoins($field);
    }

    public function get_base_coin_rate($id) {
        return Coin::GetCurrencyCalcValue($id,'currency_values.base_currency_id','currency_values.coin_id')
            ->union(Coin::GetCurrencyCalcValue($id,'currency_values.coin_id','currency_values.base_currency_id'));
    }

    public function get_coins_invoice_payment($rate,$field) {
        return  $rate->union(Coin::GetBaseCoins($field))->orderBy('name');
    }

    public function get_user_clients($id) {
        return Client::select('*')->join('user_clients','clients.id','user_clients.client_id')
            ->where('user_clients.user_id',$id);
    }

    public function generate_data_coin($coin_calc) {
        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin = $this->get_base_coin($coin_calc)->first();
        $rate = $this->get_base_coin_rate($calc_coin->id)->first();
        return ["base_coin" => $base_coin, "calc_coin" => $calc_coin, 
                "rate" => ($coin_calc == 'calc_currency_sale' ?  $rate->sale_price : $rate->purchase_price)];
    }

}
