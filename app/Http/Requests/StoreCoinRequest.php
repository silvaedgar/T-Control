<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCoinRequest extends FormRequest
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
        // $coin = $this->route('coin');
        // otra forma de obtener el id
        return [
            'symbol' => ['required', 'max:3', $this->id == 0 ? 'unique:coins' : "unique:coins,symbol,$this->id"],
            'name' => ['required', $this->id == 0 ? 'unique:coins' : "unique:coins,name,$this->id"],
        ];
    }

    public function messages()
    {
        return [
            'symbol.required' => 'Ingrese el Simbolo de la Moneda',
            'symbol.max' => 'Longitud Maxima del simbolo de moneda 3 caracteres',
            'symbol.unique' => ' Simbolo de la Moneda ya existe ',
            'name.required' => 'Ingrese el Nombre de la Moneda ',
            'name.unique' => ' Nombre de  moneda ya existe ',
        ];
    }
}
