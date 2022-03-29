<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentSupplierRequest extends FormRequest
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
            'payment_form_id' => 'required',
            'payment_date' => 'required',
            'rate_exchange' => 'required',
            'mount' => 'required'
        ];
    }
    public function attributes()
    {
        return [
            'supplier_id' => 'Proveedor',
            'coin_id' => 'Moneda o Divisa de pago',
            'payment_form_id' => 'Forma de pago',
            'payment_date' => 'Fecha',
            'rate_exchange' => 'Tasa de Cambio',
            'mount' => 'Monto a Pagar'
        ];
    }

}
