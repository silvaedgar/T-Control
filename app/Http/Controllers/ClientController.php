<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Coin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Traits\GetDataCommonTrait;

use PDF;

class ClientController extends Controller
{
    use GetDataCommonTrait;

    public function __construct() {
        $this->middleware('role')->only('index','create','edit','store','destroy');
    }

    public function index()
    {
        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
        $rate = $this->get_base_coin_rate($calc_coin->id)->first();
        // return response()->json ([
        //     'rate' => $rate,
        //     'calc_coin' => $calc_coin
        // ],200);
        $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
            'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol, 'rate' => $rate->sale_price,
            'controller' => 'Client', 'header' => 'Listado de Clientes',
            'buttons' => [['message' => 'Listado','icon' => 'print','url' => '','target'=>false],
                ['message' => 'Deudores','icon' => 'print','url' => route('clients.list_debtor'),'target'=>true],
                ['message' => 'Crear','icon' => 'person_add','url' => route('clients.create'),'target' => false]],
            'links_create' => [['message' => 'Crear Factura', 'url' =>route('sales.create')],
                    ['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')]],
            'links_header' => ['message' => '','url' => ''], 'cols' => 2];
        $clients = Client::where('status','Activo')->orderby('names')->get();
        return view('clients.index',compact('clients','data_common'));
    }

    public function create()
    {
        $data_common = ['header' => 'Crear Cliente', 'sub_header' => "", 'base_coin_id' => 0,
            'base_coin_symbol'=> '', 'rate' => 0, 'message_title' => '','message_subtitle' => '',
            'controller' => 'Client', 'buttons' => [], 'cols' => 2,
            'links_header' => ['message' => 'Ir al Listado','url' => route('clients.index')],
            'links_create' => [['message' => 'Crear Factura', 'url' =>route('sales.create')],
                    ['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')]]];
        return view('clients.create',compact('data_common'));
    }

    public function store(StoreClientRequest $request)
    {
        Client::create($request->all());
        return redirect()->route('clients.index')->with("status","Ok_Creación del Cliente $request->names");
    }

    // este show se utiliza para actualizar datos desde el cliente el $id es el usuario
    public function show($id)
    {
        $userclients = $this->get_user_clients($id)->first();
        if ($id != Auth::user()->id || $userclients == '')  // proteccion de ruta el cliente no consulte otro cliente
        {   $exist_client = ($userclients == ''?false :true);
            return view('home-auth',compact('exist_client'));
        }
        $client = Client::find($userclients->client_id);
        $userclient = "Client";
        return view('clients.edit',compact('client','userclient'));
    }

    public function edit(Client $client)
    {
        // $client = Client::find($client->id);
        $data_common = ['header' => 'Editando Cliente', 'sub_header' => "", 'base_coin_id' => 0,
            'base_coin_symbol'=> '', 'rate' => 0, 'message_title' => '','message_subtitle' => '',
            'controller' => 'Client', 'buttons' => [], 'cols' => 2,
            'links_header' => ['message' => 'Ir al Listado','url' => route('clients.index')],
            'links_create' => [['message' => 'Crear Factura', 'url' =>route('sales.create')],
                    ['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')]]];
        $userclient = "user";
        return view('clients.edit',compact('client','userclient','data_common'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $client = Client::find($client->id);
        $client->update($request->all());
        if ($request->userclient == "user")
            return redirect()->route('clients.index')->with("status","Ok_Actualización del Cliente $request->names");
        return redirect("/home")->with("status","Ok_Datos Actualizados");
        //
    }

    public function destroy(Client $client)
    {
        // $cliente = Client::find($client->id);
        if ($client->balance !=0)
            return redirect()->route('clients.index')->with("status","Error_El cliente $client->names tiene Saldo pendiente no se elimino");
        $client->status = 'Inactivo';
        $client->save();
        return redirect()->route('clients.index')->with("status","Ok_Se elimino el cliente $client->names");

    }

    public function load_movements_client($id,$calc_coin_id,$base_coin_id,$mensaje='') {
        $first = Client::GetDataSales($id,$calc_coin_id,$base_coin_id)->where('sales.status','<>','Anulada');
        $movements = Client::GetDataPayments($id,$calc_coin_id,$base_coin_id)->where('payment_clients.status','<>','Anulada');
        if ($mensaje == 'Mostrar Historicos') {
            $first = $first->where('sales.status','<>','Historico');
            $movements = $movements->where('payment_clients.status','<>','Historico');
        }
        $movements = $movements->union($first)->orderBy('date','desc')->orderBy('create','desc')->get();
        if (count($movements) == 0) {  // esto solo sucede cuando solo existe balance inicial
            $movements = Client::select('*','clients.id as client')->selectRaw("'Balance' as type")->where('id',$id)->get();
        }
        return $movements;
    }

    public function list_debtor() {
        $clients = Client::orderBy('names','asc')->where('balance','<>',0)->get();
        $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
        $rate_exchange = $this->get_base_coin_rate($calc_coin->id)->first();

        $pdf = PDF::loadView('clients.report',['clients' => $clients,'base_coins' => $calc_coin, 'tasa' => $rate_exchange->sale_price]);
        return $pdf->stream();
    }

    public function balance(Client $client,$mensaje='') {
        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
        $rate = $this->get_base_coin_rate($calc_coin->id)->first();
        $mensaje = ($mensaje=='' || $mensaje=='Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos');
        $movements = $this->load_movements_client($client->id,$calc_coin->id,$base_coin->id,$mensaje);
        $message_balance = 'Saldo: '.$movements[0]->balance.' '.($client->count_in_bs == 'S' ? $base_coin->symbol : $calc_coin->symbol);
        if ($client->count_in_bs != 'S')
            $message_balance .= ($calc_coin->symbol != $base_coin->symbol ? ' - '.number_format($movements[0]->balance * $rate->sale_price,2).$base_coin->symbol:'');
        $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
            'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol, 'rate' => $rate->sale_price,
            'controller' => 'Client', 'header' => 'Detalle de Movimientos',
            'sub_header' => 'Moneda de Calculo: '.$calc_coin->symbol.' - Tasa: '.number_format($rate->sale_price,2),
            'message_title' => 'Cliente: '.$movements[0]->names, 'message_subtitle' => $message_balance,
            'links_header' => ['message' => 'Listado de Clientes','url' => route('clients.index')],
            'links_create' => [['message' => 'Crear Factura', 'url' =>route('sales.create')],
                    ['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')]],
            'cols'=> 3];
        // return response()->json([
        //     'rate' => $rate,
        //     'data' => $data_common,
        //     'movements' => $movements
        // ], 200);
        return view('clients.balance',compact('movements','base_coin','mensaje','data_common'));
    }

    public function printbalance($id,$mensaje='') {

        if (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('User')) {
            // $userclient = $this->loaduserclients($id);
            $exist_client = true;
            $userclient =  Client::select('*')->join('user_clients','clients.id','user_clients.client_id')
                ->where('user_clients.client_id',$id)->first();
            if ($userclient->user_id <> Auth::user()->id)
                return view('home-auth',compact('exist_client'));
        }
        // $pdf = PDF::loadView('clients.printbalance',['movements' =>$movements,'symbolcoin' => $symbolcoin, 'tasa' => $tasa]);
        return $pdf->stream();
    }

    /* El id de la funcion es el del usuario */
    public function account_state($id,$mensaje='') {
        $user_client = $this->get_user_clients($id)->first();
        if ($id != Auth::user()->id || $user_client == '')  // proteccion de ruta el cliente no consulte otro cliente
        {   $exist_client = ($user_client == ''?false :true);
            return view('home-auth',compact('exist_client'));
        }
        $client = Client::find($user_client->client_id);
        return $this->balance($client,$mensaje);
    }
}
