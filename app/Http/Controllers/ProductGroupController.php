<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;

use App\Models\ProductGroup;

use App\Http\Requests\StoreProductGroupRequest;

use App\Facades\Config;

use App\Traits\ProductGroupTrait;

class ProductGroupController extends Controller
{
    use ProductGroupTrait;

    public function __construct()
    {
        // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('role.admin');
        // $this->middleware('can:users.create')->only('create');
    }

    public function index()
    {
        $config = Config::labels('ProductGroups', $this->getProductGroups()->get());
        $config['header']['title'] = 'Listado de Grupos de Productos';
        $config['isFormIndex'] = true;
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $config = Config::labels('ProductGroups');
        $config['header']['title'] = 'Creacion de Grupos de Producto';

        return view('maintenance.shared.create-edit', compact('config'));
    }

    public function store(StoreProductGroupRequest $request)
    {
        return $this->saveProductGroup($request);
    }

    public function edit(ProductGroup $productgroup)
    {
        $config = Config::labels('ProductGroups', $productgroup, true);
        $config['header']['title'] = 'Editando Grupo de Producto: ' . $productgroup->description;

        return view('maintenance.shared.create-edit', compact('productgroup', 'config'));
    }

    public function update(StoreProductGroupRequest $request)
    {
        $response = $this->saveProductGroup($request);
        return redirect()
            ->route('maintenance.productgroups.index')
            ->with('message_status', $response['message']);
    }

    public function destroy($id)
    {
        $productGroup = ProductGroup::find($id);
        $productGroup->activo = !$productGroup->activo;
        $productGroup->save();

        return redirect()
            ->route('maintenance.productgroups.index')
            ->with('status', (!$productGroup->activo ? 'Ok_Eliminación' : 'Ok_Activacion') . " de Grupo de Producto $productGroup->description");
    }
}
