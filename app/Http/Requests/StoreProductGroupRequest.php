<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductGroupRequest extends FormRequest
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
        if ($this->id == 0) {
            return [
                'description' => 'required|string|min:4|unique:product_groups',
            ];
        } else {
            return [
                'description' => "required|string|min:4|unique:product_groups,description,$this->id",
            ];
        }
    }

    public function messages()
    {
        return [
            'description.required' => 'Ingrese la Descripción',
            'description.unique' => 'La descripcion ya esta en uso',
            'description.min' => 'La descripción debe tener al menos 5 caracteres',
        ];
    }
}
