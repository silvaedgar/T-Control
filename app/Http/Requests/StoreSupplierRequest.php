<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends FormRequest
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
            'document' => ['required',Rule::unique('suppliers')->where('document_type',$this->document_type)],
            'name' => 'required|max:60|min:5',
            'contact' => 'required',
            'address' => 'required',
            'phone' => 'required'
//            regex:/^04((14)|(24)|(16)|(26)|(12))[0-9]{7}$|^02[1-9]{2}[0-9]{7}$/'
// la expresion de arriba es para el telefono funciona afuera pero aqui no
        ];
    }

    public function messages() {
        return [
            'document.required' => 'El Número de Identificación es obligatoria',
            'document.unique' => 'Número de Identificación ya existe',
            'names.required' => 'El Nombre del Proveedor es obligatorio',
            'names.max' => 'Longitud máxima de 60 caracteres para el Nombre del Proveedor',
            'names.min' => 'Longitud minima  de 5 caracteres para el Nombre del Proveedor',
            'address.required' => 'La Dirección es obligatoria',
            'phone.required' => 'Ingrese un numero telefónico'
        ];
    }

}
