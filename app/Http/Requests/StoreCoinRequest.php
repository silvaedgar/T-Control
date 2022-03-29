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
        return [
            'symbol' => "required|max:3|unique:coins",
            'name' => "required:unique:coins",
        ];
    }
    public function attributes() {
        return [
            'name' => ' "Nombre de Moneda" ',
            'symbol' => ' "Simbolo de Moneda" '

        ];
    }

}
