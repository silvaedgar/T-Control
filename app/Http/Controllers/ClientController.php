<?php

namespace App\Http\Controllers;

use App\Models\Client;

use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;

// use Illuminate\Support\Facades\Auth;
use App\Facades\Config;

use App\Traits\CoinTrait;
use App\Traits\ClientTrait;
use App\Traits\SharedTrait;

use PDF;

class ClientController extends Controller
{
    use CoinTrait, ClientTrait, SharedTrait;

    public function __construct()
    {
        $this->middleware('role')->only('index', 'create', 'edit', 'store', 'destroy');
    }

    public function index()
    {
        $config = Config::labels('Clients', $this->getClients()->get());
        $config['header']['title'] = 'Listado de Clientes';
        $config['data']['baseCoin'] = $this->getBaseCoin('base_currency')->first();
        $config['data']['calcCoin'] = $this->getBaseCoinRate($this->getBaseCoin('base_currency')->first()->id)->first();
        $config['isFormIndex'] = true;
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $config = Config::labels('Clients');
        $config['header']['title'] = 'Creando Cliente';
        $config['cols'] = 2;
        return view('shared.create-edit', compact('config'));
    }

    public function store(ClientRequest $request)
    {
        return redirect()
            ->route('clients.index')
            ->with('message_status', $this->saveClient($request)['message']);
    }

    // este show se utiliza para actualizar datos desde el cliente el $id es el usuario
    public function show($id)
    {
        // $userclients = $this->get_user_clients($id)->first();
        // if ($id != auth()->user()->id || $userclients == '') {
        //     // proteccion de ruta el cliente no consulte otro cliente
        //     $exist_client = $userclients == '' ? false : true;
        //     return view('home-auth', compact('exist_client'));
        // }
        // $client = Client::find($userclients->client_id);
        // $userclient = 'Client';
        // return view('clients.edit', compact('client', 'userclient'));
    }

    public function edit(Client $client)
    {
        // $userclient = 'user';
        $config = Config::labels('Clients', $client, true);
        $config['header']['title'] = 'Editando Cliente: ' . $client->names;
        $config['cols'] = 2;
        return view('shared.create-edit', compact('config', 'client'));
    }

    public function update(ClientRequest $request)
    {
        $response = $this->saveClient($request);
        if ($request->userclient != 'user') {
            return redirect()
                ->route('clients.index')
                ->with('message_status', $response['message']);
        }
        return redirect()
            ->route('home')
            ->with('message_status', $response['message']);
    }

    public function destroy(Client $client)
    {
        if ($client->balance != 0) {
            return redirect()
                ->route('clients.index')
                ->with('message_status', "Cliente $client->names tiene Saldo pendiente no se elimino");
        }
        $client->activo = !$client->activo;
        $client->save();
        return redirect()
            ->route('clients.index')
            ->with('message_status', "Cliente $client->names. " . ($client->activo == 0 ? 'ELIMINADO' : 'RESTAURADO') . ' CON EXITO');
    }

    public function list_debtor()
    {
        $clients = $this->getClients([['balance', '<>', 0]])->get();
        $data = $this->generateDataCoin('calc_currency_sale');
        $pdf = PDF::loadView('clients.report', ['clients' => $clients, 'base_coins' => $data['calc_coin'], 'tasa' => $data['rate']]);
        return $pdf->stream();
    }

    public function balance(Client $client, $mensaje = '')
    {
        if (!$this->isClientAuthorized($client->id)) {
            $exist_client = true;
            return view('home-auth', compact('exist_client'));
        }
        $mensaje = $mensaje == '' || $mensaje == 'Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos';
        $config = Config::labels('Clients', $client);
        $response = $this->loadConfigBalance($client, $mensaje, $config);
        $config = $response['config'];
        $movements = $response['movements'];
        return view('clients.balance', compact('movements', 'mensaje', 'config'));
    }

    public function printBalance(Client $client, $mensaje = '')
    {
        if (!$this->isClientAuthorized($client->id)) {
            $exist_client = true;
            return view('home-auth', compact('exist_client'));
        }
        $mensaje = $mensaje == '' || $mensaje == 'Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos';
        $config = Config::labels('Clients', $client);
        $response = $this->loadConfigBalance($client, $mensaje, $config);
        $config = $response['config'];
        $movements = $response['movements'];
        $pdf = PDF::loadView('clients.printbalance', ['movements' => $movements, 'config' => $config]);
        return $pdf->stream();
    }

    /* El id de la funcion es el del usuario */
    public function accountState($id, $mensaje = '')
    {
        $user_client = $this->get_user_clients($id)->first();
        if ($id != auth()->user()->id || $user_client == '') {
            // proteccion de ruta el cliente no consulte otro cliente
            $exist_client = $user_client == '' ? false : true;
            return view('home-auth', compact('exist_client'));
        }
        $client = Client::find($user_client->client_id);
        return $this->balance($client, $mensaje);
    }

    public function loadConfigBalance(Client $client, $mensaje, $config)
    {
        $config['header']['title'] = 'Detalle de Movimientos ';
        $config['buttons'] = [];
        $config['cols'] = 3;
        $rate = $this->getBaseCoinRate($this->getBaseCoin('calc_currency_sale')->first()->id)->first();
        $config['data']['calcCoin'] = $this->getBaseCoin('calc_currency_sale')->first();
        $config['data']['baseCoin'] = $this->getBaseCoin('base_currency')->first();
        $config['data']['calcCoin']->purchase_price = $rate->sale_price;
        $config['data']['calcCoin']->rate = $rate->sale_price;
        $config['router']['routePost'] = 'sales.show';
        $movements = $this->loadMovementsClient($client->id, $config['data']['calcCoin']->id, $config['data']['baseCoin']->id, $mensaje);
        $config['header']['subTitle2'] = 'Saldo: ' . $movements[0]->balance . ' ' . ($client->count_in_bs == 'S' ? $config['data']['baseCoin']->symbol : $config['data']['calcCoin']->symbol);
        if ($config['data']['baseCoin']->id != $config['data']['calcCoin']->id && $client->count_in_bs != 'S') {
            $config['header']['subTitle2'] .= ' -- ' . number_format($movements[0]->balance * $rate->sale_price, 2) . $config['data']['baseCoin']->symbol;
        }
        $config['header']['title2'] = 'Cliente: ' . $movements[0]->names;
        $config['header']['subTitle'] = 'Moneda de Calculo ' . $config['data']['calcCoin']->symbol . ' -- Tasa ' . $rate->sale_price . $config['data']['baseCoin']->symbol;
        $config['isFormIndex'] = true;
        return ['config' => $config, 'movements' => $movements];
    }
}
