<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/webhook', [App\Http\Controllers\API\ApiController::class, 'webhook']);

Route::post('/new/webhook', [App\Http\Controllers\API\ApiController::class, 'order_webhook']);

Route::get('/products',[App\Http\Controllers\API\ApiController::class,'products']);
Route::get('/categories',[App\Http\Controllers\API\ApiController::class,'categories']);
Route::post('/create-product',[App\Http\Controllers\API\ApiController::class,'product_create']);