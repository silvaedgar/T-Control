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
            'supplier_id' => 'required',
            'coin_id' => 'required',
            'rate_exchange' => 'required',
            'purchase_date' => 'required',
            'mount' => 'required',
            'product_id' => 'required'
        ];
    }

    public function attributes () {
        return [
            'supplier_id' => 'Proveedor',
            'coin_id' => 'Moneda',
            'rate_exchange' => 'Tasa de Cambio',
            'purchase_date' => 'fecha',
        ];
    }
}
