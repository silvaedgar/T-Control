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
        return [
            'description' => 'required|string|min:4|unique:product_groups'
        ];
    }

    public function attributes() {
        return [
            'description' => 'descripcion de grupo'
        ];
    }


    public function message() {

        return [
        'description.min' => 'La descripción debe tener al menos 5 caracteres',
        ];
    }
}
