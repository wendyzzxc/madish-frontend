<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmailVerificationController;

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

Route::middleware('auth:api', 'verified')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::get('email/resend', 'Api\VerificationController@resend')->name('verification.resend');

Route::get('email/verify/{id}/{hash}', 'Api\VerificationController@verify')->name('verification.verify');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Api\AuthController@logout');

    Route::get('user', 'Api\AuthController@index');
    Route::get('user/{id}', 'Api\AuthController@show');
    Route::post('user', 'Api\AuthController@store');
    Route::put('user/{id}', 'Api\AuthController@update');
    Route::delete('user/{id}', 'Api\AuthController@destroy');

    Route::get('menu', 'Api\MenuController@index');
    Route::get('menu/{id}', 'Api\MenuController@show');
    Route::post('menu', 'Api\MenuController@store');
    Route::put('menu/{id}', 'Api\MenuController@update');
    Route::delete('menu/{id}', 'Api\MenuController@destroy');

    Route::get('reservation', 'Api\ReservationController@index');
    Route::get('reservation/{id}', 'Api\ReservationController@show');
    Route::post('reservation', 'Api\ReservationController@store');
    Route::put('reservation/{id}', 'Api\ReservationController@update');
    Route::delete('reservation/{id}', 'Api\ReservationController@destroy');

    Route::post('image/{id}', 'ImagesController@upload');
});
