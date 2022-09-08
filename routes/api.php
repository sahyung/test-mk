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
Route::group(['middleware' => 'auth:api'], function () {
    // Kost
    Route::post('kosts', 'KostController@store')->middleware('isAdminOrKostOwner');
    Route::get('kosts/{id}/check_availability', 'KostController@checkAvailability');
    Route::get('kosts/owned', 'KostController@owned')->middleware('isAdminOrKostOwner');
    Route::put('kosts/{id}', 'KostController@update')->middleware('isAdminOrKostOwner');
    Route::delete('kosts/{id}', 'KostController@destroy')->middleware('isAdminOrKostOwner');

    // Users
    Route::get('users', 'UserController@index')->middleware('isAdmin');
    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
});

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('forgot', 'AuthController@forgot');
    Route::get('refresh', 'AuthController@refresh');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

// Kost
Route::get('kosts', 'KostController@index');
Route::get('kosts/{id}', 'KostController@show');
