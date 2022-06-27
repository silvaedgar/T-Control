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

Route::get('/products/{id}/categories',[ApiController::class,'loadcategories']);
Route::get('/purchases/{id}/suppliers',[PurchaseController::class,'suppliers'])->name('purchase.suppliers');

Route::get('/product_price/{id}',[ApiController::class,'product_price']);
Route::get('/search_invoice_client/{id}/{calc_coin_id}/{base_coin_id}',[ApiController::class,'search_invoice_client']);
Route::get('/search_invoice_supplier/{id}/{calc_coin_id}/{base_coin_id}',[ApiController::class,'search_invoice_supplier']);
Route::get('/rate_exchange/{id}',[ApiController::class,'rate_exchange']);

Route::get('/coins/{id}/loadcoins',[ApiController::class,'loadcoins'])->name('loadcoins');

