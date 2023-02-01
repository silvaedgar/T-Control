<?php

namespace App\Facades;

use App\Facades\DataCommonFacade;
use App\Models\Client;
use App\Traits\GetDataCommonTrait;
use Illuminate\Support\Facades\Auth;

class ProcessClient {

    use GetDataCommonTrait;

    public function load_movements($id,$calc_coin_id,$base_coin_id,$mensaje='') {
        $first = Client::GetDataSales($id,$calc_coin_id,$base_coin_id)->where('sales.status','<>','Anulada');
        $movements = Client::GetDataPayments($id,$calc_coin_id,$base_coin_id)->where('payment_clients.status','<>','Anulado');
        if ($mensaje == 'Mostrar Historicos') {
            $first = $first->where('sales.status','<>','Historico');
            $movements = $movements->where('payment_clients.status','<>','Historico');
        }

        $movements = $movements->union($first)->orderBy('date','desc')->orderBy('create','desc')->get();
        if (count($movements) == 0) {  // esto solo sucede cuando solo existe balance inicial
            $movements = Client::select('*','clients.id as client_id')->selectRaw("'Balance' as type")->where('id',$id)->get();
        }
        return $movements;
    }

    public function generate_movements(Client $client, $message) {

        $data = $this->generate_data_coin('calc_currency_sale');
        $movements = $this->load_movements($client->id,$data['calc_coin']->id,$data['base_coin']->id,$message);
        $message_balance = 'Saldo: '.$client->balance.' '.($client->count_in_bs == 'S' ?
            $data['base_coin']->symbol : $data['calc_coin']->symbol);
        if ($client->count_in_bs != 'S')
            $message_balance .= ($data['calc_coin']->symbol != $data['base_coin']->symbol ? ' - '.number_format($client->balance * $data['rate'],2).$data['base_coin']->symbol:'');
        $data['client'] = $client->names;
        $data["message_balance"] = $message_balance;
        $data['header'] = 'Detalle de Movimientos';
        $data_common = DataCommonFacade::balance('Client',$data);
        $data_common["link_print_detail"] = route('clients.print_balance',[$client->id,$message]);
        return ['data_common' => $data_common, 'movements' => $movements];
    }

    public function authorizate($id) {
        if (!auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('User')) { // el usuario es cliente consulta solo su edo de cuenta
            $exist_client = true;
            $user_client =  Client::select('*')->join('user_clients','clients.id','user_clients.client_id')
                ->where('user_clients.client_id',$id)->first();
            if ($user_client == null)
                return false;
            if ($user_client->user_id <> auth()->user()->id)
                return false;
        }
        return true;
    }

}
