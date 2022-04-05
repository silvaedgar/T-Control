<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'supplier_id' => 'gt:0',
            'coin_id' => 'gt:0',
            'rate_exchange' => 'gt:0',
            'purchase_date' => 'required',
            'mount' => 'gt:0',
        ];
    }

    public function attributes () {
        return [
            'supplier_id' => 'Proveedor',
            'coin_id' => 'Moneda',
            'rate_exchange' => 'Tasa de Cambio',
            'purchase_date' => 'Fecha',
            'mount' => "Monto"
        ];
    }

    public function messages() {

        return [
        'mount.gt' => 'Debe Ingresar los productos de la factura',
        'supplier.gt' => 'Debe Seleccionar el Proveedor',
        'rate_exchange.gt' => 'Ingrese el valor de la tasa de cambio',
        'purchase_date.required' => 'Ingrese la Fecha'
    ];
    }

}
