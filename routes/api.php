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

    Route::get('/posts', 'PostController@index');
    Route::get('/posts/recommend', 'PostController@recommend');
    Route::get('/posts/top', 'PostController@top');
    Route::get('/posts/hot', 'PostController@hot');

    Route::get('/post/{token}', 'PostController@show');

    Route::get('/comment/new', 'CommentController@new');

    Route::get('/blogrolls', 'BlogrollController@index');
    Route::get('/notices', 'NoticeController@index');

    Route::get('/categories', 'CategoryController@list');
    Route::get('/category/{name}', 'CategoryController@articles');

    Route::get('/tag/{name}', 'TagController@articles');

    Route::group(['middleware' => ['auth:api', 'scopes:blog-web']], function(){
        Route::get('user', 'UserController@user');

        Route::post('comment/post/{token}', 'UserController@comment');

        Route::post('comment/reply/{token}', 'UserController@reply');

        Route::post('praise/post/{token}', 'UserController@praise');
    });
});


