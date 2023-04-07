<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentSupplierRequest extends FormRequest
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
            'coin_id' => 'required',
            'payment_form_id' => 'gt:0',
            'payment_date' => 'required',
            'rate_exchange' => 'required',
            'mount' => 'required|gt:0',
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
            'mount' => 'Monto a Pagar',
        ];
    }

    public function messages()
    {
        return [
            'supplier_id.gt' => 'Debe seleccionar el proveedor',
            'payment_form_id.gt' => 'Debe seleccionar la forma de pago',
            'mount.gt' => 'Debe ingresar un monto de pago mayor a 0',
        ];
    }
}
