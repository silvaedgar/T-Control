<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'code' => ['required',Rule::unique('products')->ignore($this->id)],
            'name' => ['required',Rule::unique('products')->ignore($this->id)
                            ->where('name',$this->namecode)],
            'category_id' => 'gt:0',
            'tax_id' => 'gt:0',
            'imagefile' => 'image',
            'cost_price' => 'numeric|gt:-1',
            'sale_price' => 'numeric|gt:-1',
        ];
    }

    public function messages() {
        return [
            'code.required' => 'El Código del Producto es obligatorio',
            'code.unique' => 'Código del Producto ya existe',
            'name.required' => 'Nombre o Descripción del Producto es obligatorio',
            'name.unique' => 'Nombre o Descripción del Producto ya existe',
            'tax_id.gt' => 'Seleccione tipo de Impuesto del Producto',
            'category_id.gt' => 'Seleccione Grupo y Categoria del Producto',
            'imagefile.image' => 'Formato de Imagen Invalido',
            'cost_price.numeric' => 'Costo del producto tiene que ser numerico',
            'cost_price.gt' => 'Costo del producto no puede ser negativo',
            'sale_price.numeric' => 'Precio de Venta del producto tiene que ser numerico',
            'sale_price.gt' => 'Costo del producto no puede ser negativo',
        ];

    }


}
