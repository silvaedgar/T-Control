<?php

namespace App\Traits;

use App\Models\Tax;
use App\Http\Requests\StoreTaxRequest;

use App\Traits\SharedTrait;

trait TaxTrait
{
    use SharedTrait;

    // public function getTaxes($filter = [])
    // {
    //     return Tax::where($filter)->orderby('description', 'asc');
    // }

    public function saveTax(StoreTaxRequest $request)
    {
        $response = $this->saveModel('Tax', $request);
        if ($response['success']) {
            $response['message'] = "Impuesto $request->description " . ($request->id == 0 ? 'creado' : 'actualizado') . ' con exito';
        }
        return redirect()
            ->route('maintenance.taxes.index')
            ->with('message_status', $response['message']);
    }
}
