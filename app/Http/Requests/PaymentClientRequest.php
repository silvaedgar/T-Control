<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentClientRequest extends FormRequest
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
            'client_id' => 'Cliente',
            'coin_id' => 'Moneda o Divisa de pago',
            'payment_form_id' => 'Forma de pago',
            'payment_date' => 'Fecha',
            'rate_exchange' => 'Tasa de cambio',
            'mount' => 'Monto a Pagar',
        ];
    }

    public function messages()
    {
        return [
            'payment_form_id.gt' => 'Seleccione la Forma de Pago',
            'client_id.gt' => 'Seleccione el Cliente',
            'mount.gt' => 'El monto a pagar debe ser mayor a 0',
        ];
    }
}
