<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\ProductGroup;

use App\Http\Requests\StoreProductCategoryRequest;

use App\Facades\Config;

use App\Traits\ProductCategoryTrait;
use App\Traits\ProductGroupTrait;

class ProductCategoryController extends Controller
{
    use ProductCategoryTrait, ProductGroupTrait;

    public function __construct()
    {
        // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('role.admin');
    }

    public function index()
    {
        $config = Config::labels('ProductCategories', ProductCategory::GetProductCategories()->get());
        $config['header']['title'] = 'Listado de Categorias de Productos';
        $config['isFormIndex'] = true;
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $groups = $this->getProductGroups([['activo', '=', 1]])->get();
        if (count($groups) == 0) {
            return redirect()
                ->route('maintenance.productcategories.index')
                ->with('message_status', 'No existen Grupos de Productos. Debe crearlos primero');
        }
        $config = Config::labels('ProductCategories');
        $config['header']['title'] = 'Creando Grupos de Producto';
        return view('maintenance.shared.create-edit', compact('groups', 'config'));
    }

    public function store(StoreProductCategoryRequest $request)
    {
        // $response = $this->saveProductCategory($request);
        return redirect()
            ->route('maintenance.productcategories.index')
            ->with('message_status', $this->saveProductCategory($request)['message']);
        //     ->with('status', "Ok_Creación de la Categoria de Producto $request->description");
    }

    public function edit(ProductCategory $productcategory)
    {
        $groups = $this->getProductGroups([['activo', '=', 1]])->get();
        $config = Config::labels('ProductCategories', $productcategory, true);
        $config['header']['title'] = 'Editando Categoria de Producto: ' . $productcategory->description;
        return view('maintenance.shared.create-edit', compact('groups', 'config', 'productcategory'));
    }

    public function update(StoreProductCategoryRequest $request, ProductCategory $productCategory)
    {
        return redirect()
            ->route('maintenance.productcategories.index')
            ->with('message_status', $this->saveProductCategory($request)['message']);
        //     ->with('status', "Ok_Actualización de Categoria de Producto $request->description");
        //
    }

    public function destroy($id)
    {
        $productCategories = ProductCategory::find($id);
        $productCategories->activo = !$productCategories->activo;
        $productCategories->save();
        return redirect()
            ->route('maintenance.productcategories.index')
            ->with('status', (!$productCategories->activo ? 'Ok_Eliminación' : 'Ok_Activacion') . " de Grupo de Producto $productCategories->description");
    }
}
