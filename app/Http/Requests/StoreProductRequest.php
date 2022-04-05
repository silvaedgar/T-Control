<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'code' => "required|string|unique:products,code",
            'name' => "required|string|unique:products,name"
        ];
    }

    public function attributes() {
        return [
            'code' => 'Codigo del Producto',
            'name' => 'Descripcion del Producto'
        ];

    }
}
