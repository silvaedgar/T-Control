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

    public function messages()
    {
        return [
            'supplier_id.gt' => config('messageerror.select') . ' el proveedor',
            'payment_form_id.gt' => config('messageerror.select') . ' Forma de Pago',
            'mount.gt' => config('messageerror.mountGreaterZero') . ' a pagar',
        ];
    }
}
