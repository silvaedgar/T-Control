<?php

namespace App\Traits;

use App\Models\Supplier;

use App\Http\Requests\StoreSupplierRequest;

use App\Traits\SharedTrait;

trait SupplierTrait
{
    use SharedTrait;

    public function getSuppliers($filter = [])
    {
        return Supplier::where($filter)->orderby('name', 'asc');
    }

    public function loadMovementsSupplier($id, $calc_coin_id, $base_coin_id, $mensaje = '')
    {
        $first = Supplier::GetDataSales($id, $calc_coin_id, $base_coin_id)->where('sales.status', '<>', 'Anulada');
        $movements = Supplier::GetDataPayments($id, $calc_coin_id, $base_coin_id)->where('payment_suppliers.status', '<>', 'Anulado');
        if ($mensaje == 'Mostrar Historicos') {
            $first = $first->where('sales.status', '<>', 'Historico');
            $movements = $movements->where('payment_suppliers.status', '<>', 'Historico');
        }

        $movements = $movements->union($first)
            ->orderBy('date', 'desc')->orderBy('create', 'desc')->get();

        if (count($movements) == 0) {
            // esto solo sucede cuando solo existe balance inicial
            $movements = Supplier::select('*', 'suppliers.id as supplier_id')
                ->selectRaw("'Balance' as type")->where('id', $id)->get();
        }
        return $movements;
    }

    public function saveSupplier(StoreSupplierRequest $request)
    {
        $response = $this->saveModel('Supplier', $request);
        if ($response['success']) {
            $response['message'] = "Proveedor $request->name " . ($request->id == 0 ? 'creado' : 'actualizado') . ' con exito';
        }
        return $response;
    }
}
