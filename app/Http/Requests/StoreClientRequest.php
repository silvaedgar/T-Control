<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'document' => 'required',
            'names' => 'required|string|max:60',
            'address' => 'required',
// la expresion de arriba es para el telefono funciona afuera pero aqui no
        ];
    }
}
