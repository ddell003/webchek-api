<?php

use App\Http\Api\LoginController;
use App\Http\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::post('login', '\\' . LoginController::class . '@login');
Route::resource('/accounts', '\\' . \App\Http\Api\AccountController::class);
Route::group(['middleware' => ['throttle:20,1', 'auth:api']], function () {
    Route::resource('/users', '\\' . UserController::class);
    Route::resource('/apps', '\\' . \App\Http\Api\AppController::class);
    Route::resource('/apps/{app}/tests', '\\' . \App\Http\Api\TestController::class);
    Route::resource('apps/{app}/tests/{test}/logs', '\\' . \App\Http\Api\TestLogController::class);

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
