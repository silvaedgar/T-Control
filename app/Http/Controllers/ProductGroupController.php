<?php

namespace App\Http\Controllers;

use App\Models\ProductGroup;

use App\Http\Requests\StoreProductGroupRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

// use App\Http\Requests\UpdateProductGroupRequest;

class ProductGroupController extends Controller
{
    public function __construct() {      // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('role.admin');
    // $this->middleware('can:users.create')->only('create');
    }

    public function index()
    {
        $productgroups = ProductGroup::orderBy('description')->get();
        return view('maintenance.product-groups.index',compact('productgroups'));
    }

    public function create()
    {
      return view('maintenance.product-groups.create');
        //
    }

    public function store(StoreProductGroupRequest $request)
    {
        ProductGroup::create($request->all());
        return redirect()->route('maintenance.productgroups.index')->with('status',"Ok_Creación del Grupo Producto  $request->description");
    }
    public function show(ProductGroup $productRubric)
    {
        return view('maintenance.productgroups.index');
        //
    }

    public function edit($id)
    {
        $productgroup = ProductGroup::find($id);
        return view('maintenance.product-groups.edit',compact('productgroup'));
        //
    }

    public function update(Request $request)
    {
        $request->validate ([
            'description' => "required|string|min:4|unique:product_groups,description,$request->id"
        ]);
        $productgroup = ProductGroup::find($request->id);
        $productgroup->update($request->all());
        return redirect()->route('maintenance.productgroups.index')->with('status',"Ok_Actualización del Grupo de Producto $request->description");
        //
    }

    public function destroy($id)
    {
        $productgroup = ProductCategory::find($id);
        $productgroup->status = 'Inactivo';
        $productgroup->save();

        return redirect()->route('maintenance.productgroups.index')->with('status',"Ok_Eliminación de Grupo de Producto $productgroup->description");
    }
}
