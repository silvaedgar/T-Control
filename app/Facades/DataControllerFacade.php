<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DataControllerFacade extends Facade
{

    protected static function getFacadeAccessor() {
        return "DataController";
    }
}
