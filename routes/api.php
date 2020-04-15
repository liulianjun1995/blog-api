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

Route::group(['namespace' => 'Api'], function () {

    Route::get('/carousels', 'CarouselController@list');

    Route::group(['prefix' => 'article'], function () {
        Route::get('list', 'ArticleController@list');
        Route::get('tops', 'ArticleController@tops');
        Route::get('hots', 'ArticleController@hots');
        Route::get('recommends', 'ArticleController@recommends');
        Route::get('random', 'ArticleController@random');
        Route::post('{id}/detail', 'ArticleController@detail');
    });

    Route::group(['prefix' => 'user', 'middleware' => ['login-authenticate']], function () {
        Route::get('info', 'UserController@user');
        Route::post('logout', 'UserController@logout');
    });

});


