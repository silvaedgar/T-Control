<?php

namespace App\Providers;

use App\UserClient;
use Illuminate\Support\ServiceProvider;

class UserClientServiceProvider extends ServiceProvider
{
    public $bindings = [
        'UserClient' => UserClient::class,
    ];

    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        return Config('municipio');
        //
    }
}
