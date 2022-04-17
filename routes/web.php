<?php

use Illuminate\Support\Facades\Route;
use App\UserClientFacade;


use App\Http\Controllers\ClientController;
use App\Http\Controllers\CoinController;
use App\Http\Controllers\CoinBaseController;
use App\Http\Controllers\CurrencyValueController;
use App\Http\Controllers\PaymentFormController;
use App\Http\Controllers\PaymentClientController;
use App\Http\Controllers\PaymentSupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UnitMeasureController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserClientController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/','App\Http\Controllers\HomeController@welcome')->name('/');
Route::get('/whoaim','App\Http\Controllers\HomeController@whoaim')->name('whoaim');
Route::get('/home-guest','App\Http\Controllers\HomeController@homeguest')->name('home-guest');
Route::get('/home-tcontrol','App\Http\Controllers\HomeController@hometcontrol')->name('home-tcontrol');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Auth::routes();

// Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);


    Route::get('clients/accountstate/{id}',[ClientController::class,'accountstate'])->name('clients.accountstate');
    Route::get('clients/printbalance/{id}/{mensaje?}',[ClientController::class,'printbalance'])->name('clients.printbalance');
    Route::get('clients/balance/{id}/{mensaje?}',[ClientController::class,'balance'])->name('clients.balance');
    Route::get('clients/listdebtor',[ClientController::class,'listdebtor'])->name('clients.listdebtor');
    Route::resource('clients', ClientController::class)->names('clients');

    Route::get('suppliers/listcreditors',[SupplierController::class,'listcreditors'])->name('suppliers.listcreditors');
    Route::get('suppliers/balance/{id}/{mensaje?}',[SupplierController::class,'balance'])->name('suppliers.balance');
    Route::resource('suppliers', SupplierController::class)->names('suppliers');


    Route::get('products/listprint',[ProductController::class,'listprint'])->name('products.listprint');
    Route::resource('products', ProductController::class)->names('products');

    Route::resource('purchases', PurchaseController::class)->names('purchases');


    Route::resource('productcategories', ProductCategoryController::class)->names('maintenance.productcategories');

    Route::post('sales/filtersale', [SaleController::class,'filtersale'])->name('sales.filtersale');
    Route::post('sales/report',[SaleController::class,'report'])->name('sales.report');
    Route::get('sales/print/{id}',[SaleController::class,'print'])->name('sales.print');
    Route::resource('sales', SaleController::class)->names('sales');

    Route::post('paymentclients/filterpayment', [PaymentClientController::class,'filterpayment'])->name('paymentclients.filterpayment');
    Route::post('paymentclients/report', [PaymentClientController::class,'report'])->name('paymentclients.report');
    Route::resource('paymentclients', PaymentClientController::class)->names('paymentclients');


    Route::resource('productgroups', ProductGroupController::class)->names('maintenance.productgroups');
    Route::resource('paymentforms', PaymentFormController::class)->names('maintenance.paymentforms');
    Route::resource('coins', CoinController::class)->names('maintenance.coins');
    Route::resource('currencyvalues', CurrencyValueController::class)->names('maintenance.currencyvalues');
    Route::resource('taxes', TaxController::class)->names('maintenance.taxes');
    Route::resource('unitmeasures', UnitMeasureController::class)->names('maintenance.unitmeasures');
    Route::resource('paymentsuppliers', PaymentSupplierController::class)->names('paymentsuppliers');
    Route::resource('users', UserController::class)->names('users');
    Route::resource('userclients', UserClientController::class)->names('userclients');

    Route::resource('roles', RoleController::class)->names('roles');

    Route::get('coinbase', [CoinBaseController::class,'index'])->name('coinbase.index');
    Route::put('coinbase', [CoinBaseController::class,'update'])->name('coinbase.update');

});


