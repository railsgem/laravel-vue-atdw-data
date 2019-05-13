<?php

use Illuminate\Http\Request;

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

Route::middleware('api')->get('products','ApiController@getProductList');
Route::middleware('api')->get('product','ApiController@getProductDetails');
Route::middleware('api')->get('areas','ApiController@getAreas');
Route::middleware('api')->get('regions','ApiController@getRegions');
