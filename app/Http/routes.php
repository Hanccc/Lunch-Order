<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
//    Route::auth();
    Route::get('/login', 'Auth\AuthController@showLoginForm');

    Route::get('/logout', 'Auth\AuthController@logout');

    Route::post('/login', 'Auth\AuthController@login');

    Route::group(['middleware' => 'auth'], function(){

        Route::get('/', 'HomeController@index');

        Route::get('/order/{id}/{type}', ['as' => 'order', 'uses' => 'HomeController@order']);

        Route::get('/cancel', 'HomeController@cancel');

        Route::get('/admin', 'MenuController@index');

        Route::get('/feature', function(){
            return view('feature');
        });

        Route::get('/menu', 'MenuController@index');
        Route::post('/addMenu', 'MenuController@addMenu');
        Route::get('changeStatus', 'MenuController@changeStatus');

    });
});
