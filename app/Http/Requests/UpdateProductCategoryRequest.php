<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductCategoryRequest extends FormRequest
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
            'description' => ['required','min:3',Rule::unique('product_categories')
                    ->ignore($this->id)->where('group_id',$this->group_id)],
            'group_id' => 'gt:0'
        ];
    }

    public function messages() {
        return [
            'description.required' => 'La descripción es obligatoria',
            'description.unique' => 'Descripcion de Categoria en Grupo ya existe',
            'description.min' => 'La Descripcion de Categoria  debe contener al menos 3 caracteres',
            'group_id.gt' => 'Debe seleccionar un grupo'
        ];
    }

}
