<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'client_id' => 'required',
            'coin_id' => 'required',
            'rate_exchange' => 'required',
            'sale_date' => 'required',
        ];
    }

    public function attributes () {
        return [
            'client_id' => 'Cliente',
            'coin_id' => 'Moneda',
            'rate_exchange' => 'Tasa de Cambio',
            'sale_date' => 'fecha',
        ];
    }
}
