<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProcessPaymentClientFacade extends Facade
{

    protected static function getFacadeAccessor() {
        return "ProcessPaymentClient";
    }
}
