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
// use App\Http\Controllers\RoleController;

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

Route::get('/', 'App\Http\Controllers\HomeController@welcome')->name('/');
Route::get('/whoaim', 'App\Http\Controllers\HomeController@whoaim')->name('whoaim');
Route::get('/home-guest', 'App\Http\Controllers\HomeController@homeguest')->name('home-guest');
Route::get('/home-tcontrol', 'App\Http\Controllers\HomeController@hometcontrol')->name('home-tcontrol');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

    Route::get('clients/account_state/{id}', [ClientController::class, 'accountState'])->name('clients.account_state');
    Route::get('clients/print_balance/{client}/{mensaje?}', [ClientController::class, 'printBalance'])->name('clients.print_balance');
    Route::get('clients/balance/{client}/{mensaje?}', [ClientController::class, 'balance'])->name('clients.balance');
    Route::get('clients/list_debtor', [ClientController::class, 'list_debtor'])->name('clients.list_debtor');
    Route::resource('clients', ClientController::class)->names('clients');

    Route::get('suppliers/list_creditors', [SupplierController::class, 'list_creditors'])->name('suppliers.listcreditors');
    Route::get('suppliers/balance/{supplier}/{mensaje?}', [SupplierController::class, 'balance'])->name('suppliers.balance');
    Route::resource('suppliers', SupplierController::class)->names('suppliers');

    Route::get('products/list_print', [ProductController::class, 'printProducts'])->name('products.list_print');
    Route::get('products/prueba', [ProductController::class, 'prueba'])->name('products.prueba');

    Route::resource('products', ProductController::class)->names('products');

    Route::post('purchases/filter', [PurchaseController::class, 'filter'])->name('purchases.filter');
    Route::get('purchases/print/{id}', [PurchaseController::class, 'print'])->name('purchases.print');
    Route::resource('purchases', PurchaseController::class)->names('purchases');

    Route::resource('productcategories', ProductCategoryController::class)->names('maintenance.productcategories');

    Route::post('sales/filter', [SaleController::class, 'filter'])->name('sales.filter');
    Route::get('sales/print/{sale}', [SaleController::class, 'print'])->name('sales.print');
    Route::get('sales/printList', [SaleController::class, 'printList'])->name('sales.printList');

    Route::resource('sales', SaleController::class)->names('sales');

    Route::post('paymentclients/filter', [PaymentClientController::class, 'filter'])->name('paymentclients.filter');
    Route::resource('paymentclients', PaymentClientController::class)->names('paymentclients');

    Route::post('paymentsupplier/filter', [PaymentSupplierController::class, 'filter'])->name('paymentsuppliers.filter');
    Route::resource('paymentsuppliers', PaymentSupplierController::class)->names('paymentsuppliers');

    Route::resource('productgroups', ProductGroupController::class)->names('maintenance.productgroups');
    Route::resource('paymentforms', PaymentFormController::class)->names('maintenance.paymentforms');
    Route::resource('coins', CoinController::class)->names('maintenance.coins');
    Route::resource('currencyvalues', CurrencyValueController::class)->names('maintenance.currencyvalues');
    Route::resource('taxes', TaxController::class)->names('maintenance.taxes');
    Route::resource('unitmeasures', UnitMeasureController::class)->names('maintenance.unitmeasures');

    Route::resource('users', UserController::class)->names('users');
    Route::resource('userclients', UserClientController::class)->names('userclients');

    // Route::resource('roles', RoleController::class)->names('roles');

    Route::get('coinbase', [CoinBaseController::class, 'index'])->name('coinbase.index');
    Route::put('coinbase', [CoinBaseController::class, 'update'])->name('coinbase.update');
});
