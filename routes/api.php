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

// Auth Only
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/home', 'Frontend\HomeController@home');
    Route::get('/profile', 'Bungie\ProfileController@index');

    Route::prefix('/users')->group( function () {
       Route::get('/all', 'Auth\UserController@all');
       Route::patch('/assignRole/{id}', 'Auth\UserController@setUserRole');
       Route::delete('/delete/{id}', 'Auth\UserController@destroy');
       Route::get('/single/{id}', 'Auth\UserController@single');
    });

    Route::prefix('/roles')->group( function () {
        Route::get('/all', 'Auth\RoleController@index');
        Route::get('/permissions', 'Auth\RoleController@permissions');
        Route::post('/store', 'Auth\RoleController@store');
        Route::get('/edit/{id}', 'Auth\RoleController@edit');
        Route::patch('/update/{id}', 'Auth\RoleController@update');
        Route::delete('/delete/{id}', 'Auth\RoleController@destroy');
        Route::get('/single/{id}', 'Auth\RoleController@single');
    });

    Route::prefix('/profile')->group( function () {
        Route::get('/all', 'Bungie\ProfileController@index');
        Route::get('/data', 'Bungie\ProfileController@profileStats');
        Route::get('/characters', 'Bungie\Profilecontroller@characters');
        Route::get('/character/{item}', 'Bungie\ProfileController@getCharacterInfos');
        Route::get('/character/stats/{id}', 'Bungie\CharacterController@getCharacterStats');
    });

    Route::prefix('/posts')->group( function () {
        Route::get('/index', 'Backend\PostController@index');
        Route::get('/create', 'Backend\PostController@create');
        Route::get('/edit/{id}', 'Backend\PostController@edit');
        Route::post('/store', 'Backend\PostController@store');
        Route::patch('/update/{id}', 'Backend\PostController@update');
        Route::delete('/delete/{id}', 'Backend\PostController@destroy');
        Route::patch('/publish/{id}', 'Backend\PostController@publish');
        Route::get('/get/{id}', 'Frontend\PostController@get');
        Route::get('/byCategory/{id}', 'Frontend\PostController@byCategory');
    });

    Route::prefix('/categories')->group( function () {
        Route::get('/index', 'Backend\CategoryController@index');
        Route::get('/edit/{id}', 'Backend\CategoryController@edit');
        Route::post('/store', 'Backend\CategoryController@store');
        Route::patch('/update/{id}', 'Backend\CategoryController@update');
        Route::delete('/delete/{id}', 'Backend\CategoryController@destroy');
        Route::get('/get/{id}', 'Frontend\CategoryController@get');
    });

    Route::prefix('/tags')->group( function () {
        Route::get('/all', 'Backend\TagController@index');
        Route::get('/edit{id}', 'Backend\TagController@edit');
        Route::post('/store', 'Backend\TagController@store');
        Route::put('/update/{id}', 'Backend\TagController@update');
        Route::delete('/delete/{id}', 'Backend\TagController@destroy');
    });

    Route::get('allPost', 'Frontend\PostController@all');
    Route::get('getPost/{id}', 'Frontend\PostController@get');

    Route::get('allCategories', 'Frontend\CategoryController@all');
    Route::get('getCategories/{id}', 'Frontend\CategoryController@get');
});

// Public Only
Route::group(['middleware' => 'guest:api'], function () {

    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});

// Public & Auth
Route::get('/user', 'Auth\UserController@current');

Route::prefix('/clan')->group( function () {
    Route::get('/infos', 'Bungie\ClanController@infos');
    Route::get('/members', 'Bungie\ClanController@members');
    Route::get('/admins-founder', 'Bungie\ClanController@adminsAndFounder');
    Route::get('/banner', 'Bungie\ClanController@getClanBanner');
    Route::get('/single-member/{id}/{type}', 'Bungie\ClanController@singleMember');
    Route::get('/member/infos/{id}/{type}', 'Bungie\ClanController@memberInfos');
});

Route::prefix('/manifest')->group( function () {
    Route::get('/check', 'Bungie\ManifestController@checkManifest');
    Route::get('/tables', 'Bungie\ManifestController@getAllTables');
    Route::get('/query/{table}/{id}','Bungie\ManifestController@getSingleDefinition');
    Route::get('/definition/{def}', 'Bungie\ManifestController@getDefinition');
});
