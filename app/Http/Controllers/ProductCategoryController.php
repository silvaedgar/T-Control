<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\ProductGroup;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ProductCategoryController extends Controller
{
    public function __construct() {      // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('can:maintenance');
    // $this->middleware('can:users.create')->only('create');
    }

    public function index()
    {
        $productcategories = ProductCategory::all();
        return view('maintenance.product-categories.index',compact('productcategories'));
    }

    public function create()
    {
      $groups = ProductGroup::where('status','Activo')->orderby('description')->get();
      if (count($groups) == 0) {
        $productcategories = ProductCategory::all();
        return view('maintenance.product-categories.index',compact('productcategories'));
      }
      return view('maintenance.product-categories.create',compact('groups'));
        //
    }

    public function store(StoreProductCategoryRequest $request)
    {
        ProductCategory::create($request->all());
        return redirect()->route('maintenance.productcategories.index')->with('status',"Ok_Creación de la Categoria de Producto $request->description");
    }
    public function show(ProductCategory $productcategory)
    {
        return view('maintenance.product-categories.index');
        //
    }

    public function edit($id)
    {
        $productcategory = ProductCategory::find($id);
        $groups = ProductGroup::where('status','Activo')->orderBy('description')->get();

        return view('maintenance.product-categories.edit',compact('productcategory','groups'));
        //
    }

    public function update(Request $request)
    {
        $request->validate ([
            'description' => "required|string|min:3|unique:product_groups,description,$request->id"
        ]);
        $productcategory = ProductCategory::find($request->id);
        $productcategory->update($request->all());
        return redirect()->route('maintenance.productcategories.index')->with('status',"Ok_Actualización de Categoria de Producto $request->description");
        //
    }

    public function destroy($id)
    {
        $productcategories = ProductCategory::find($id);
        $productcategories->status = 'Inactivo';
        $productcategories->save();
        return redirect()->route('maintenance.productcategories.index')->with('status',"Ok_Eliminación de Categoria de Producto $productcategories->description");
    }
}
