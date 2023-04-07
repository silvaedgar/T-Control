<?php

namespace App\Traits;

use App\Models\ProductGroup;

use App\Http\Requests\StoreProductGroupRequest;

use App\Traits\SharedTrait;

trait ProductGroupTrait
{
    use SharedTrait;

    public function getProductGroups($filter = [])
    {
        return ProductGroup::where($filter)->orderBy('description', 'asc');
    }

    public function saveProductGroup(StoreProductGroupRequest $request)
    {
        $response = $this->saveModel('ProductGroup', $request);
        if ($response['success']) {
            $response['message'] = "Grupo $request->description " . ($request->id == 0 ? 'creado' : 'actualizado') . ' con exito';
        }
        return $response;
    }

    // public function getDisplayInfoProductGroup($update = false, $productGroup = null)
    // {
    //     return [
    //         'layout' => ['activePage' => 'maintenance', 'titlePage' => 'Modulo Grupos de Productos'],
    //         'header' => ['title' => '', 'subTitle' => 'Detalle del Grupo', 'form' => 'maintenance.product-groups.form'],
    //         'buttons' => ['title' => 'Crear Grupo', 'url' => 'maintenance.productgroups.create', 'icon' => 'person_add'],
    //         'table' => ['header' => ['Item', 'Descripción', 'Acción'], 'include' => 'maintenance.product-groups.table'],
    //         'router' => ['routePost' => 'maintenance.productgroups.' . ($update ? 'update' : 'store'), 'routeIndex' => 'maintenance.productgroups.index', 'item' => '$productgroup'],
    //         'data' => ['collection' => $productGroup, 'update' => $update],
    //     ];
    // }
}
