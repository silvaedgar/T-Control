<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
        // $item = 1;
        // foreach ($this->request as $key => $item) {
        //     if($key == 'product_id') {
        //         foreach($item as $id) {
        //             $renglon[] = ["product_id" => $id];
        //         }
        //         dd($renglon);

        //     }
            // $results[] = array("product_id"=>$this->request->product_id[$key], "tax_id"=>$this->request->tax_id[$key],
            //     "item"=>$item,"quantity"=>$this->request->quantity[$key], "price"=>$this->request->price[$key],
            //     "tax"=>$this->request->tax[$key]);
        return [
            'supplier_id' => 'gt:0',
            'coin_id' => 'gt:0',
            'rate_exchange' => 'gt:0',
            'purchase_date' => 'required',
            'mount' => 'gt:0',
        ];
    
    }

    public function messages()
    {
        return [
        'mount.gt' => 'Debe Ingresar los productos de la factura',
        'supplier_id.gt' => 'Debe Seleccionar el Proveedor',
        'rate_exchange.gt' => 'El valor de la tasa de cambio es obligatorio',
        'purchase_date.required' => 'Ingrese la Fecha'
        ];
    }

}
