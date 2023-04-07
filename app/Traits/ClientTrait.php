<?php

namespace App\Traits;

use App\Models\Client;

use App\Http\Requests\ClientRequest;

use App\Traits\SharedTrait;

trait ClientTrait
{
    use SharedTrait;

    public function getClients($filter = [])
    {
        return Client::where($filter)->orderby('names', 'asc');
    }

    public function loadMovementsClient($id, $calc_coin_id, $base_coin_id, $mensaje = '')
    {
        $first = Client::GetDataSales($id, $calc_coin_id, $base_coin_id)->where('sales.status', '<>', 'Anulada');
        $movements = Client::GetDataPayments($id, $calc_coin_id, $base_coin_id)->where('payment_clients.status', '<>', 'Anulado');
        if ($mensaje == 'Mostrar Historicos') {
            $first = $first->where('sales.status', '<>', 'Historico');
            $movements = $movements->where('payment_clients.status', '<>', 'Historico');
        }

        $movements = $movements
            ->union($first)
            ->orderBy('date', 'desc')
            ->orderBy('create', 'desc')
            ->get();
        if (count($movements) == 0) {
            // esto solo sucede cuando solo existe balance inicial
            $movements = Client::select('*', 'clients.id as client_id')
                ->selectRaw("'Balance' as type")
                ->where('id', $id)
                ->get();
        }
        return $movements;
    }

    public function saveClient(ClientRequest $request)
    {
        $response = $this->saveModel('Client', $request);
        if ($response['success']) {
            $response['message'] = "Cliente $request->names " . ($request->id == 0 ? 'creado' : 'actualizado') . ' con exito';
        }
        return $response;
    }

    public function isClientAuthorized($id) {
        if (!auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('User')) { // el usuario es cliente consulta solo su edo de cuenta
            $exist_client = true;
            $user_client =  Client::select('*')
                ->join('user_clients','clients.id','user_clients.client_id')
                ->where('user_clients.client_id',$id)->first();
            if ($user_client == null || $user_client->user_id <> auth()->user()->id)
                return false;
            // if ($user_client->user_id <> auth()->user()->id)
            //     return false;
        }
        return true;
    }

}
