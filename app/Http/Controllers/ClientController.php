<?php

namespace App\Http\Controllers;

use App\Models\Client;

use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;


class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('clients.index',compact('clients'));
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
        // echo $client;
        // $cliente = Client::find($client->id);
        // $cliente->update(['status' => 'Inactivo']);
        // $client->save();
        // echo $cliente;

        // return redirect()->route('clients.index')->with("status","Ok_Se elimino el cliente $client->names con exito");

    }

    //
}
