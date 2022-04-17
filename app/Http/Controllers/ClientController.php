<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Coin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use PDF;

class ClientController extends Controller
{
    public function __construct() {
        $this->middleware('role')->only('index','create','edit','store','destroy');
    }

    public function index()
    {
        $symbolcoin = Coin::where('calc_currency_sale','S')->where('status','Activo')->first();
        $clients = Client::where('status','Activo')->orderby('names')->get();
        return view('clients.index',compact('clients','symbolcoin'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        Client::create($request->all());
        return redirect()->route('clients.index')->with("status","Ok_Creación del Cliente $request->names");
    }

    // este show se utiliza para actualizar datos desde el cliente el $id es el usuario
    public function show($id)
    {
        $userclients = $this->loaduserclients($id);
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
        $client = Client::find($client->id);
        $userclient = "user";
        return view('clients.edit',compact('client','userclient'));
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
        $cliente = Client::find($client->id);
        if ($cliente->balance !=0)
            return redirect()->route('clients.index')->with("status","Error_El cliente $client->names tiene Saldo pendiente no se elimino");
        $client->status = 'Inactivo';
        $client->save();
        return redirect()->route('clients.index')->with("status","Ok_Se elimino el cliente $client->names");

    }

    public function loaduserclients($id) {
        return Client::select('*')->join('user_clients','clients.id','user_clients.client_id')
            ->where('user_clients.user_id',$id)->first();
    }

    public function loadmovementsclients($id,$mensaje='') {
        if ($mensaje == 'Mostrar Historicos') {
            $saleclient = Client::select('sale_date as date','symbol','mount','clients.names',
                    'balance','sales.id','clients.id as client_id','sales.created_at as create')
                ->selectRaw("'Compras' as type")->selectRaw('mount * rate_exchange as mountbalance')
                ->join('sales','clients.id','sales.client_id')->join('coins','sales.coin_id','coins.id')
                ->where('client_id',$id)->where('sales.status','<>','Historico');
            $movements =  Client::select('payment_date as date','symbol','mount','clients.names','balance',
                    'payment_clients.id','clients.id as client_id','payment_clients.created_at as create')
                ->selectRaw("'Pagos' as type")->selectRaw('mount * rate_exchange  as mountbalance')
                ->join('payment_clients','clients.id','payment_clients.client_id')
                ->join('coins','payment_clients.coin_id','coins.id')->where('client_id',$id)
                ->where('payment_clients.status','<>','Historico')->union($saleclient)
                ->orderBy('date','desc')->orderBy('create','desc')->get();
        }
        else {
            $saleclient = Client::select('sale_date as date','symbol','mount','clients.names',
                    'balance','sales.id','clients.id as client_id','sales.created_at as create')
                ->selectRaw("'Compras' as type")->selectRaw('mount * rate_exchange as mountbalance')
                ->join('sales','clients.id','sales.client_id')->join('coins','sales.coin_id','coins.id')
                ->where('client_id',$id);
            $movements =  Client::select('payment_date as date','symbol','mount','clients.names',
                    'balance','payment_clients.id','clients.id as client_id','payment_clients.created_at as create')
                ->selectRaw("'Pagos' as type")->selectRaw('mount * rate_exchange  as mountbalance')
                ->join('payment_clients','clients.id','payment_clients.client_id')
                ->join('coins','payment_clients.coin_id','coins.id')->where('client_id',$id)
                ->union($saleclient)->orderBy('date','desc')->orderBy('create','desc')->get();
        }
        if (count($movements) == 0) {  // esto solo sucede cuando solo existe balance inicial
                $movements = Client::select('*','clients.id as client_id')->selectRaw("'Balance' as type")->where('id',$id)->get();
        }
        return $movements;
    }

    public function listdebtor() {
        $clients = Client::orderBy('names','asc')->where('balance','<>',0)->get();
        $pdf = PDF::loadView('clients.report',['clients' => $clients]);
        return $pdf->stream();
    }

    public function balance($id,$mensaje='') {

        $mensaje = ($mensaje=='' || $mensaje=='Sin Historicos' ? 'Mostrar Historicos' : 'Sin Historicos');
        $movements = $this->loadmovementsclients($id,$mensaje);
        $backlist = true;
        return view('clients.balance',compact('movements','id','mensaje','backlist'));
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
        $movements = $this->loadmovementsclients($id,$mensaje);
        $pdf = PDF::loadView('clients.printbalance',['movements' =>$movements]);
        return $pdf->stream();
    }

    /* El id de la funcion es el del usuario */
    public function accountstate($id,$mensaje='') {
        $userclient = $this->loaduserclients($id);
        if ($id != Auth::user()->id || $userclient == '')  // proteccion de ruta el cliente no consulte otro cliente
        {   $exist_client = ($userclient == ''?false :true);
            return view('home-auth',compact('exist_client'));
        }
        $movements = $this->loadmovementsclients($userclient->client_id,$mensaje);
        return view('clients.balance',compact('movements','mensaje','id'));
    }
}
