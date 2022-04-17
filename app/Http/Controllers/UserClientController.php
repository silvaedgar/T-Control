<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\UserClient;
use \Illuminate\Support\Facades\DB;


class UserClientController extends Controller
{
    public function __construct() {
        $this->middleware('role.admin');
    }

    public function loadusers() {
        return User::role('Client')->select('users.*')->leftjoin('user_clients','users.id','user_clients.user_id')
                ->where('user_clients.user_id',null)->get();
    }

    public function loadclients() {
        return Client::select('clients.*')->leftjoin('user_clients','user_clients.client_id','clients.id')
            ->leftjoin('users','users.id','user_clients.user_id')->where('users.id',null)
            ->orderBy('names')->get();
    }

    public function index()
    {
        $users = $this->loadusers();
        $clients = $this->loadclients();
        return view('user-clients.index',compact('users','clients'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // return $request;

        DB::beginTransaction();
        $item=0;

        foreach ($request->client_id as $key => $value) {
            if ($request->client_id[$key] != 0) {
                $user_client = new UserClient;
                $user_client->client_id = $request->client_id[$key];
                $user_client->user_id = $request->user_id[$key];
                $user_client->save();
                $item++;
            }
        }
        DB::commit();
        $message = 'Error_No se procesaron los datos. Verifique que se asignaron los clientes a usuarios';
        if ($item > 0) {
            $message = 'Ok_Asignación de Usuarios a Clientes procesada con exito';
        }
        $users = $this->loadusers();
        $clients = $this->loadclients();
        return view('user-clients.index',compact('users','clients'))->with('status',$message);
    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
