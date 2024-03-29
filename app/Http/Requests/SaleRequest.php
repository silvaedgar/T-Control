<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
            'client_id' => 'gt:0',
            'coin_id' => 'gt:0',
            'rate_exchange' => 'gt:0',
            'sale_date' => 'required',
            'mount' => 'gt:0',
        ];
    }

    public function messages()
    {
        return [
            'mount.gt' => 'Ingrese los productos de la factura',
            'client_id.gt' => 'Seleccione el Cliente',
            'rate_exchange.gt' => 'Ingrese el valor de la tasa de cambio',
            'purchase_date.required' => 'Ingrese la Fecha de la Factura',
        ];
    }
}
