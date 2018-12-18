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

Route::get('users', 'UserControllerAPI@index');
Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile');

Route::get('users/{id}', 'UserControllerAPI@show');
Route::post('users', 'UserControllerAPI@store');
Route::put('users/{id}', 'UserControllerAPI@update');
Route::delete('users/{id}', 'UserControllerAPI@destroy');
Route::middleware('auth:api')->post('users/{id}/uploadPhoto', 'UserControllerAPI@uploadPhoto');
Route::middleware('auth:api')->put('users/{id}/toggleShift', 'UserControllerAPI@toggleShift');

Route::get('items', 'ItemControllerAPI@index');
Route::get('items/{id}', 'ItemControllerAPI@show');
Route::post('items', 'ItemControllerAPI@store');
Route::put('items/{id}', 'ItemControllerAPI@update');
Route::delete('items/{id}', 'ItemControllerAPI@destroy');

Route::get('tables', 'TableControllerAPI@index');
Route::get('tables/{table_number}', 'TableControllerAPI@show');
Route::post('tables', 'TableControllerAPI@store');
Route::put('tables/{table_number}', 'TableControllerAPI@update');
Route::delete('tables/{table_number}', 'TableControllerAPI@destroy');

Route::middleware('auth:api')->get('orders', 'OrderControllerAPI@index');
Route::middleware('auth:api')->get('orders/{id}', 'OrderControllerAPI@show');
Route::middleware('auth:api')->post('orders/{id}/deliver', 'OrderControllerAPI@deliver');
Route::middleware('auth:api')->post('orders', 'OrderControllerAPI@store');
Route::middleware('auth:api')->put('orders/{id}', 'OrderControllerAPI@update');
Route::middleware('auth:api')->delete('orders/{id}', 'OrderControllerAPI@destroy');

Route::middleware('auth:api')->get('meals', 'MealControllerAPI@index');
//Route::middleware('auth:api')->get('meals/{id}', 'MealControllerAPI@show');
Route::middleware('auth:api')->get('meals/{id}/orders', 'MealControllerAPI@orders');
//Route::middleware('auth:api')->post('meals', 'MealControllerAPI@store');
//Route::middleware('auth:api')->put('meals/{id}', 'MealControllerAPI@update');
//Route::middleware('auth:api')->delete('meals/{id}', 'MealControllerAPI@destroy');

Route::post('login', 'LoginControllerAPI@login')->name('login');
 
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');

Route::middleware('auth:api')->get('teste', function () {
    return response()->json(['msg'=>'Só um teste'], 200);
});
