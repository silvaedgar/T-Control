<?php

namespace App;

use Illuminate\Support\Facades\Facade;

class UserClientFacade extends Facade 
{
    protected static function getFacadeAccessor()
    {
        return "UserClient";
    }

}