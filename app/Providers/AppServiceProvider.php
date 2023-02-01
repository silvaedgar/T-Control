<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Facades\DataCommon;
use App\Facades\DataController;
use App\Facades\ProcessPaymentClient;
use App\Facades\ProcessPaymentSupplier;
use App\Facades\ProcessClient;
use App\Facades\ProcessSale;
use App\Facades\ProcessPurchase;
use App\Facades\ProcessProduct;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('DataCommon', function($app) {
            return new DataCommon();
        });

        $this->app->bind('DataController', function($app) {
            return new DataController();
        });

        $this->app->bind('ProcessPaymentClient', function($app) {
            return new ProcessPaymentClient();
        });

        $this->app->bind('ProcessPaymentSupplier', function($app) {
            return new ProcessPaymentSupplier();
        });


        $this->app->bind('ProcessClient', function($app) {
            return new ProcessClient();
        });

        $this->app->bind('ProcessSale', function($app) {
            return new ProcessSale();
        });

        $this->app->bind('ProcessPurchase', function($app) {
            return new ProcessPurchase();
        });

        $this->app->bind('ProcessProduct', function($app) {
            return new ProcessProduct();
        });

        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
