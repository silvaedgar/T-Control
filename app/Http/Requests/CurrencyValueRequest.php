<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyValueRequest extends FormRequest
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
            'coin_id' => 'gt:0',
            'base_currency_id' => 'gt:0',
            'purchase_price' => 'gt:0',
            'sale_price' => 'gt:0',
        ];
    }

    public function messages()
    {
        return [
            'coin_id.gt' => 'Seleccione la Moneda base de la Relacion',
            'base_currency_id.gt' => 'Seleccione la Moneda a Relacionar',
            'purchase_price.gt' => 'Ingrese el Precio de Compra',
            'sale_price.gt' => 'Ingrese el Precio de Venta',
        ];
    }
}
