<?php

namespace App\Facades;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\PaymentSupplier;
use App\Models\PaymentForm;
use App\Models\Purchase;
use App\Models\Coin;
use App\Models\Supplier;

use App\Traits\CoinTrait;
use App\Traits\CalculateMountsTrait;

class ProcessPaymentSupplier
{
    use CoinTrait, CalculateMountsTrait;

    public function getDataPayment($id = 0)
    {
        $data = $this->generateDataCoin('calc_currency_purchase');
        if ($id > 0) {
            $data['invoice'] = PaymentSupplier::with('Supplier', 'Coin', 'PaymentForm')
                ->where('id', $id)
                ->first();
            $data['mount_other'] = $data['calc_coin']->id != $data['base_coin']->coin_id ? $this->mount_other($data['invoice'], $data['calc_coin']) : 0;
        }
        $data['suppliers'] = $id > 0 ? [$data['invoice']->supplier] : Supplier::GetSuppliers()->get();
        $data['payment_forms'] =
            $id > 0
                ? [$data['invoice']->PaymentForm]
                : PaymentForm::where('activo', 1)
                    ->orderBy('payment_form')
                    ->get();
        $data['coins'] = $id > 0 ? [$data['invoice']->coin] : $this->getCoinsInvoicePayment($this->getBaseCoinRate($data['calc_coin']->id), 'calc_currency_purchase')->get();
        $data['header'] = $id > 0 ? 'Pago a Proveedor' : 'Crear Pago a Proveedor';

        return $data;
    }
}
