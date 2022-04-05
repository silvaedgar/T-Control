<?php

use Illuminate\Support\Facades\Route;


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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);

    Route::get('suppliers/listprint',[SupplierController::class,'listprint'])->name('suppliers.listprint');
    Route::get('suppliers/balance/{id}',[SupplierController::class,'balance'])->name('suppliers.balance');

    Route::resource('suppliers', SupplierController::class)->names('suppliers');

    Route::get('clients/listprint',[ClientController::class,'listprint'])->name('clients.listprint');
    Route::resource('clients', ClientController::class)->names('clients');

    Route::get('products/listprint',[ProductController::class,'listprint'])->name('products.listprint');
    Route::resource('products', ProductController::class)->names('products');

    Route::resource('productcategories', ProductCategoryController::class)->names('maintenance.productcategories');

    Route::resource('sales', SaleController::class)->names('sales');

    Route::resource('purchases', PurchaseController::class)->names('purchases');
    Route::resource('productgroups', ProductGroupController::class)->names('maintenance.productgroups');
    Route::resource('paymentforms', PaymentFormController::class)->names('maintenance.paymentforms');
    Route::resource('coins', CoinController::class)->names('maintenance.coins');
    Route::resource('currencyvalues', CurrencyValueController::class)->names('maintenance.currencyvalues');
    Route::resource('taxes', TaxController::class)->names('maintenance.taxes');
    Route::resource('unitmeasures', UnitMeasureController::class)->names('maintenance.unitmeasures');
    Route::resource('paymentsuppliers', PaymentSupplierController::class)->names('paymentsuppliers');
    Route::resource('paymentclients', PaymentClientController::class)->names('paymentclients');
    Route::resource('users', UserController::class)->names('users');

    Route::get('coinbase', [CoinBaseController::class,'index'])->name('coinbase.index');
    Route::put('coinbase', [CoinBaseController::class,'update'])->name('coinbase.update');

});


// Route::group(['middleware' => 'auth'], function () {
// 	Route::get('table-list', function () {
// 		return view('pages.table_list');
// 	})->name('table');

// 	Route::get('typography', function () {
// 		return view('pages.typography');
// 	})->name('typography');

// 	Route::get('icons', function () {
// 		return view('pages.icons');
// 	})->name('icons');

// 	Route::get('map', function () {
// 		return view('pages.map');
// 	})->name('map');

// 	Route::get('notifications', function () {
// 		return view('pages.notifications');
// 	})->name('notifications');

// 	Route::get('rtl-support', function () {
// 		return view('pages.language');
// 	})->name('language');

// 	Route::get('upgrade', function () {
// 		return view('pages.upgrade');
// 	})->name('upgrade');
// });

