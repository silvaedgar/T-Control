<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait SharedTrait
{
    public function processSuccess($collection, $message = 'Proceso Exitoso')
    {
        return ['success' => true, 'collection' => $collection, 'message' => $message];
    }

    public function processFail($message, $th = null)
    {
        return ['success' => false, 'message' => $message . ($th == null ? '' : ' ' . $th->getMessage())];
    }

    public function saveModel($model, $data, $filter = [])
    {
        if ($filter == []) {
            $filter = ['id', '=', $data['id']];
        }
        if ($model == 'Permission' || $model == 'Role') {
            $model = 'Spatie\\Permission\\Models\\' . $model;
        } else {
            $model = 'App\\Models\\' . $model;
        }
        try {
            if ($data->id == 0) {
                return $this->processSuccess($model::create($data->all()), 'Registro creado exitosamente');
            } else {
                // dd($model);
                $response = $model::find($data->id);
                $response->update($data->all());
                return $this->processSuccess($response, 'Registro actualizado exitosamente');
            }
        } catch (\Throwable $th) {
            return $this->processFail('Error grabando datos en tabla ' . $model, $th);
        }
    }

    public function headerInfoFill($config, $fields, $collection = null)
    {
        $config = $this->loadCoinType($fields['field'], $fields['price'], $config, $fields['isPayment']);
        // 'calc_currency_purchase', 'purchase_price', $config, true);
        if ($collection != null) {
            $config['header']['title2'] = 'Monto: ' . number_format($collection->mount, 2) . ' ' . $collection->coin->symbol;
            if ($collection->coin_id == $config['data']['calcCoin']->id) {
                $config['header']['subTitle2'] = 'en ' . $config['data']['baseCoin']->symbol . ' ' . number_format($collection->mount * $collection->rate_exchange, 2);
            } else {
                $config['header']['subTitle2'] = 'en ' . $config['data']['calcCoin']->symbol . ' ' . number_format($collection->mount / $collection->rate_exchange, 2);
            }
        } else {
            $config['header']['title2'] = '0.00 ' . $config['data']['calcCoin']->symbol;
            $config['header']['subTitle2'] = '0.00 ' . $config['data']['calcCoin']->symbol;
        }
        return $config;
    }
}
