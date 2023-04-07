<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            // 'code' => ['required',Rule::unique('products')->ignore($this->id)],
            // 'name' => ['required',Rule::unique('products')->ignore($this->id)
            //     ->where('name', $this->namecode)],
            // otra forma de validar con eloquent

        return [
            'code' => ['required', $this->id == 0 ? 'unique:products,code' : "unique:products,code,$this->id"],
            'name' => ['required',$this->id == 0 ? 'unique:products,code' : "unique:products,code,$this->id"],
            'category_id' => 'gt:0',
            'tax_id' => 'gt:0',
            'image_file' => $this->image_file ? 'image:max:2048 | mimes:jpeg,png,jpg,tiff,gif,bmp' : '',
            'cost_price' => 'numeric|gt:-1',
            'sale_price' => 'numeric|gt:-1',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'El Código del Producto es obligatorio',
            'code.unique' => 'Código del Producto ya existe',
            'name.required' => 'Nombre o Descripción del Producto es obligatorio',
            'name.unique' => 'Nombre o Descripción del Producto ya existe',
            'tax_id.gt' => 'Seleccione tipo de Impuesto del Producto',
            'category_id.gt' => 'Seleccione Categoria del Producto',
            'image_file.image' => 'Formato de Imagen Invalido',
            'image_file.max' => 'Tamaño no valido de la Imagen',
            'cost_price.numeric' => 'Costo del producto tiene que ser numerico',
            'cost_price.gt' => 'Costo del producto no puede ser negativo',
            'sale_price.numeric' => 'Precio de Venta del producto tiene que ser numerico',
            'sale_price.gt' => 'Costo del producto no puede ser negativo',
        ];

    }
}
