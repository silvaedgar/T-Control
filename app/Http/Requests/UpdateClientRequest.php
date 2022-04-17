<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
            // regex:/^V[0-9]{6,9}$/|^[J|G][0-9]{9}$|^E[0-9]{8}$/   Esta expresion regulkar funciona en regexr pero aqui no averiguar
            'document' => ['required',Rule::unique('clients')->ignore($this->id)->where('document_type',$this->document_type)],
            'names' => 'required|max:60|min:5',
            'address' => 'required',
        ];
    }

    public function messages() {
        return [
            'document.required' => 'El Número de Identificación es obligatoria',
            'document.unique' => 'Número de Identificación ya existe',
            'names.required' => 'El Nombre del cliente es obligatorio',
            'names.max' => 'Longitud máxima de 60 caracteres para el Nombre del cliente',
            'names.min' => 'Longitud minima  de 5 caracteres para el Nombre del cliente',
            'address.required' => 'La Dirección es obligatoria',
        ];
    }
}
