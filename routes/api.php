<?php

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


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::resource('articles','ArticleController');
//Route::post('/articles/add2',[\App\Api\Controllers\ArticleController::class,'add2']);
Route::get('test/index',[\App\Api\Controllers\TestController::class,'index']);
Route::get('test/cache',[\App\Api\Controllers\TestController::class,'cache']);
