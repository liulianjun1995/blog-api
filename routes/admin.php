<?php

use Illuminate\Support\Facades\Route;

/*
| Admin Routes
*/

Route::group(['namespace' => 'Admin'], function(){

    Route::post('auth/login', 'AuthController@login');

    Route::group(['middleware' => ['passport-administrators', 'login-authenticate', 'scope:blog-admin']], function() {

        Route::group(['prefix' => 'auth'], function(){
            Route::get('info', 'AuthController@info');
            Route::post('logout', 'AuthController@logout');
            Route::post('avatar', 'AuthController@avatar');
        });

        Route::group(['prefix' => 'admin'], function(){
            Route::get('options', 'AdminController@options');
        });

        Route::group(['prefix' => 'oss'], function(){
            Route::post('signature', 'OssController@signature');
        });

        Route::group(['prefix' => 'article'], function(){
            Route::get('list', 'ArticleController@list');
            Route::get('{id}/detail', 'ArticleController@detail');
            Route::post('create', 'ArticleController@create');
            Route::post('{id}/update', 'ArticleController@update');
            Route::post('{id}/profile', 'ArticleController@profile');
        });

        Route::group(['prefix' => 'category'], function(){

            Route::get('options', 'CategoryController@options');
            Route::get('list', 'CategoryController@list');
            Route::post('order', 'CategoryController@order');

        });

    });

});

