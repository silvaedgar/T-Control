<?php

namespace App\Traits;

trait CalculateMountsTrait {

    public function mount_other($invoice,$calc_coin,$base_coin) {
       return ($invoice->simbolo == $calc_coin->symbol ?
                $base_coin->symbol.': '.number_format($invoice->mount * $invoice->rate_exchange,2) :
                $calc_coin->symbol.': '.number_format($invoice->mount / $invoice->rate_exchange,2));
    }
}
