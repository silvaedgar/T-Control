<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaxRequest extends FormRequest
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
            'percent' => 'min:0|unique:taxes',
            'description' => 'required|unique:taxes'
        ];
    }

    public function messages() {
        return [
            'percent.min' => "Ingrese un Porcentaje de Impuesto > 0",
            'percent.unique' => "Porcentaje de Impuesto ya existe",
            'description.required' => "La descripción es obligatoria",
            'description.unique' => 'Descripción ya existe'

        ];
    }

}
