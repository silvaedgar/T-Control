<?php

namespace App\Traits;

trait CalculateMountsTrait {

    public function mount_other($invoice,$calc_coin) {
       return ($invoice->coin_id == $calc_coin->id ?
                $invoice->mount * $invoice->rate_exchange :
                $invoice->mount / $invoice->rate_exchange);
    }
}
