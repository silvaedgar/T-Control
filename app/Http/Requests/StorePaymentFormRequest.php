<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentFormRequest extends FormRequest
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
            'payment_form' => 'required|max:20|min:3|unique:payment_forms,payment_form',
            'description' => 'required|min:5|max:50',            //
        ];
    }

    public function messages() {
        return [
            'payment_form.required' => ' Tiene que ingresar la Forma de Pago',
            'payment_form.unique' => 'Forma de Pago ya existente',
            'payment_form.max' => 'Longitud maxima de la Forma de Pago de 20 caracteres',
            'payment_form.min' => 'Longitud minima de la Forma de Pago de 3 caracteres',
            'description.required' => 'Tiene que ingresar la descripción de la Forma de Pago',
            'description.min' => 'Longitud minima de la descripción de la forma de pago de 5 caracteres',
            'description.max' => 'Longitud maxima de la descripción de la forma de pago de 50 caracteres',
        ];
    }
}

