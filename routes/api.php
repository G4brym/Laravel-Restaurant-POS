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


Route::middleware('auth:api')->get('orders', 'OrderControllerAPI@index');
Route::middleware('auth:api')->get('orders/{id}', 'OrderControllerAPI@show');
Route::middleware('auth:api')->post('orders', 'OrderControllerAPI@store');
Route::middleware('auth:api')->put('orders/{id}', 'OrderControllerAPI@update');
Route::middleware('auth:api')->delete('orders/{id}', 'OrderControllerAPI@destroy');

/*
Caso prefiram usar Resource Routes para o user, podem implementar antes as rotas:
NOTA: neste caso, o parâmetro a receber nos métodos do controlador é user e não id

Route::apiResource('users','UserControllerAPI');
Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
*/

Route::post('login', 'LoginControllerAPI@login')->name('login');
 
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');


Route::middleware('auth:api')->get('teste', function () {
    return response()->json(['msg'=>'Só um teste'], 200);
});
