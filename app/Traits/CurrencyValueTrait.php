<?php

namespace App\Traits;

use App\Models\CurrencyValue;
use Illuminate\Support\Facades\DB;

trait CurrencyValueTrait
{
    public function storeCurrencyValue($data)
    {
        DB::beginTransaction();
        try {
            CurrencyValue::where([['base_currency_id', $data['base_currency_id']], ['coin_id', $data['coin_id']]])
                ->orwhere([['base_currency_id', $data['coin_id']], ['coin_id', $data['base_currency_id']]])
                ->update(['activo' => false]);
            CurrencyValue::create($data);
            $message = 'Ok_Se establecieron el Precio Compra y Venta de la moneda ';
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            $message = 'Error_No se pudo actualizar el  precio Compra Venta de la moneda. ' . $th->getMessage();
        }
        return $message;
    }
}
