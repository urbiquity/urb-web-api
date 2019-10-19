<?php

use Illuminate\Http\Request;

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



Route::group([
    'prefix' => 'v1',
], function() {

    // region Auth

    Route::group([
        "prefix" => "auth"
    ], function () {

        Route::post('login', [ 'as' => 'login', 'uses' => 'AuthController@login']);
        Route::post('signup', 'AuthController@signup');

        Route::post('profile', 'AuthController@update_profile');

        Route::group([
            'middleware' => 'auth:api'
        ], function() {
            Route::get('logout', 'AuthController@logout');
            Route::get('user', 'AuthController@user');
        });

    });

    // endregion Auth


    // region DataSets

    Route::group([
        "namespace" => "DataSets",
        "prefix" => "data_set"
    ], function () {

        Route::get('delete/{id}', 'DataSetController@delete');
        Route::get('fetch/{id}', 'DataSetController@fetch');
        Route::get('generate', 'DataSetController@generate' );
        Route::post('define', 'DataSetController@define');

    });

    // endregion DataSets

    // region Maps

    Route::group([
        "namespace" => "Maps",
        "prefix" => "map"
    ], function () {

        Route::get('delete/{id}', 'MapController@delete');
        Route::get('fetch/{id}', 'MapController@fetch');
        Route::get('generate_map', 'DataSetController@generate' );

        Route::post('define', 'MapController@define');

    });

    // endregion Maps

});