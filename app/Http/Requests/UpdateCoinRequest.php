<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoinRequest extends FormRequest
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

    public function rules()
    {
        $coin = $this->route('coin');
        return [
            'symbol' => 'required|max:3|unique:coins,symbol,' . $coin,
            'name' => "required",
        ];
    }

    public function messages() {
        return [
            'name.unique' => 'Nombre de moneda ya existe ',
            'name.required' => 'Nombre de Moneda es obligatorio',
            'symbol.unique' => 'Simbolo de Moneda ya existe',
            'symbol.required' => 'Simbolo de Moneda es obligatorio',
            'symbol.max' => 'Longitud máxima de 3 caracteres para del Simbolo de Moneda',
        ];
    }
}
