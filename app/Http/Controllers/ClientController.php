<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Traits\GetDataCommonTrait;
use App\Facades\DataCommonFacade;
use App\Facades\ClientFacade;

use PDF;

class ClientController extends Controller
{
    use GetDataCommonTrait;

    public function __construct() {
        $this->middleware('role')->only('index','create','edit','store','destroy');
    }

    public function index()
    {
        $data = $this->generate_data_coin('calc_currency_sale');
        $data['header'] = 'Listado de Clientes';
        $data_common = DataCommonFacade::index('Client',$data);
        $clients = Client::where('status','Activo')->orderby('names')->get();
        return view('clients.index',compact('clients','data_common'));
    }

    public function create()
    {
        $data_common = DataCommonFacade::create('Client',['header'=>'Crear Cliente']);
        return view('clients.create',compact('data_common'));
    }

    public function store(StoreClientRequest $request)
    {
        Client::create($request->all());
        return redirect()->route('clients.index')->with("message_status","Cliente $request->names. CREADO CON EXITO");
    }

    // este show se utiliza para actualizar datos desde el cliente el $id es el usuario
    public function show($id)
    {
        $userclients = $this->get_user_clients($id)->first();
        if ($id != auth()->user()->id || $userclients == '')  // proteccion de ruta el cliente no consulte otro cliente
        {   $exist_client = ($userclients == ''?false :true);
            return view('home-auth',compact('exist_client'));
        }
        $client = Client::find($userclients->client_id);
        $userclient = "Client";
        return view('clients.edit',compact('client','userclient'));
    }

    public function edit(Client $client)
    {
        $data_common = DataCommonFacade::edit('Client',['header'=>'Editando Cliente']);
        $userclient = "user";
        return view('clients.edit',compact('client','userclient','data_common'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->all());
        if ($request->userclient == "user")
            return redirect()->route('clients.index')->with("message_status","Datos del Cliente $request->names. ACTUALIZADO CON EXITO");
        return redirect("/home")->with("status","Ok_Sus Datos fueron Actualizados");
        //
    }

    public function destroy(Client $client)
    {
        if ($client->balance !=0)
            return redirect()->route('clients.index')->with("message_status","Cliente $client->names tiene Saldo pendiente no se elimino");
        $client->status = 'Inactivo';
        $client->save();
        return redirect()->route('clients.index')->with("message_status","Cliente $client->names. ELIMINADO CON EXITO");

    }

    public function list_debtor() {
        $clients = Client::orderBy('names','asc')->where('balance','<>',0)->get();
        $data = $this->generate_data_coin('calc_currency_sale');
        // $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
        // $rate_exchange = $this->get_base_coin_rate($calc_coin->id)->first();
        $pdf = PDF::loadView('clients.report',['clients' => $clients,'base_coins' => $data['calc_coin'], 'tasa' => $data['rate']]);
        return $pdf->stream();
    }

    public function balance($id,$mensaje='') {

        $client = Client::find($id);
        $mensaje = ($mensaje=='' || $mensaje=='Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos');
        $data_movements =  ClientFacade::generate_movements($client,$mensaje);
        $movements = $data_movements['movements'];
        $data_common = $data_movements['data_common'];
        return view('clients.balance',compact('movements','mensaje','data_common'));
    }

    public function print_balance($id,$message='') {

        $exist_client = true;
        if (!ClientFacade::authorizate($id)) 
            return view('home-auth',compact('exist_client'));
        $client = Client::find($id);
        $data_movements = ClientFacade::generate_movements($client,$message);
        $pdf = PDF::loadView('clients.printbalance',['movements' =>$data_movements['movements'], 'data_common' => $data_movements['data_common']]);
        return $pdf->stream();
    }

    /* El id de la funcion es el del usuario */
    public function account_state($id,$mensaje='') {
        $user_client = $this->get_user_clients($id)->first();
        if ($id != auth()->user()->id || $user_client == '')  // proteccion de ruta el cliente no consulte otro cliente
        {   $exist_client = ($user_client == ''?false :true);
            return view('home-auth',compact('exist_client'));
        }
        // $client = Client::find($user_client->client_id);
        return $this->balance($user_client->client_id,$mensaje);
    }
}
