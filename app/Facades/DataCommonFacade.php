<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DataCommonFacade extends Facade
{

    protected static function getFacadeAccessor() {
        return "DataCommon";
    }
}
