<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::get('/', function () {
//     return response()->json();
// });

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('me', 'AuthController@me');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('logout', 'AuthController@logout');
});

Route::group(['prefix' => 'category'], function ($router) {
    Route::get('/', 'CategoryController@index');
});

Route::group(['prefix' => 'laporan', 'middleware' => ['auth']], function ($router) {
    Route::get('/', 'LaporanController@index');
    Route::post('/', 'LaporanController@store');
    Route::get('/{id}', 'LaporanController@show');
    Route::put('/{id}', 'LaporanController@update');
    Route::delete('/{id}', 'LaporanController@destroy');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth']], function ($router) {
    Route::get('/laporanku', 'LaporanController@getByUser');
});

Route::group(['prefix' => 'home', 'middleware' => ['auth']], function ($router) {
    Route::get('/', 'LaporanController@getCategoryCount');
    Route::get('/{request}', 'LaporanController@getByCategory');
});

Route::get('/images/{file_name}', 'LaporanController@getImage');