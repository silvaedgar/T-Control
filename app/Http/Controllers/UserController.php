<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    // ---------------------------------------------------------------
    // This function shows a way to perform route protection. RoleController is another way
    // -----------------------------------------------------------------
    public function __construct() {      // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('can:maintenance');
        // $this->middleware('can:users.create')->only('create');

    }

    public function index() {

        $users = User::all();
        return view('users.index',compact('users'));
    }

    public function store(Request $request) {

        $request->validate ([
            'name' => 'required|string|max:30',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        DB::beginTransaction();
        $user = User::create([
            'name' =>$request->name,
            'email' =>$request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->syncRoles($request->input('roles',[]));
        DB::commit();
        return redirect()->route('users.index')->with('status',"Ok_Usuario $request->name creado con exito");
    }

    public function create() {

        $roles = Role::all();
        return view('users.create',compact('roles'));
    }

    public function edit($id) {

        $roles = Role::all();
        $user = User::find($id);
        $user->load('roles');

        return view('users.edit',compact('roles','user'));
    }

    public function update(Request $request,$id) {

        $request->validate ([
            'name' => 'required|string|max:30',
            'email' => "required|string|email|unique:users,email,$id",
            'password' => 'required|string|min:8'
        ]);

        DB::beginTransaction();
        $user = User::find($id);
        $user->update([
            'name' =>$request->name,
            'email' =>$request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->syncRoles($request->input('roles',[]));
        DB::commit();
        return redirect()->route('users.index')->with('status',"Ok_Usuario $request->name actualizado con exito");
    }




}
