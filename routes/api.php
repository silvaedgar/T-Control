<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/purchases/{id}/product_price',[ProductController::class,'product_price'])->name('product.price');
Route::get('/currencyvalues/{id}/rate_exchange',[ApiController::class,'rate_exchange']);
Route::get('/products/{id}/categories',[ApiController::class,'loadcategories']);
Route::get('/purchases/{id}/suppliers',[PurchaseController::class,'suppliers'])->name('purchase.suppliers');
Route::get('/sales/{id}/clients',[SaleController::class,'clients'])->name('sale.clients');
Route::get('/coins/{id}/loadcoins',[ApiController::class,'loadcoins'])->name('loadcoins');
