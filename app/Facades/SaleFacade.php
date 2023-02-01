<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Facades\ProcessSale;

class SaleFacade extends Facade {

    protected static function getFacadeAccessor() {
        return "ProcessSale";
    }
}



