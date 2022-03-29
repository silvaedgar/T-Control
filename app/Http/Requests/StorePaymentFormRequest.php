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
            'payment_form' => 'required|string|max:20|unique:payment_forms',
            'description' => 'required|string|min:5',            //
        ];
    }

    public function attributes() {
        return [
            'description' => 'descripcion de forma de pago',
            'payment_form' => ' forma de pago'
        ];
    }

}
