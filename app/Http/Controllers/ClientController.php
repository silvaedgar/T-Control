<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Coin;

use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use PDF;

class ClientController extends Controller
{
    public function index()
    {
        $symbolcoin = Coin::where('calc_currency_sale','S')->where('status','Activo')->first();

        $clients = Client::where('status','Activo')->orderBy('names')->get();
        return view('clients.index',compact('clients','symbolcoin'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        Client::create($request->all());
        $request->session()->flash('status', 'Creado');
        return redirect()->route('clients.index')->with("status","Ok_Creación del Cliente $request->names");
    }

    public function show(Client $client)
    {
        //
    }
    public function edit(Client $client)
    {
        $client = Client::find($client->id);
        return view('clients.edit',compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $request->validate ([
            'document' => "required|string|unique:clients,document,$client->id"
        ]);
        $client = Client::find($client->id);
        $client->update($request->all());
        return redirect()->route('clients.index')->with("status","Ok_Actualización del Cliente $request->names");
        //
    }

    public function destroy(Client $client)
    {
        $cliente = Client::find($client->id);
        $client->status = 'Inactivo';
        $client->save();

        return redirect()->route('clients.index')->with("status","Ok_Se elimino el cliente $client->names con exito");

    }

    public function listprint() {

        $clients = Client::orderBy('names')->where('balance','>',0)->get();
        $pdf = PDF::loadView('clients.report',['clients' => $clients]);
        // return view('clients.report',compact('clients'));
        // $pdf = PDF::loadHTML('<h1>Test</h1>');
        return $pdf->stream();
    }

    //
}
