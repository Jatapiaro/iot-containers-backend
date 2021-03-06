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
    'as' => 'api.v1.',
    'prefix' => 'v1',
    'namespace' => 'Api\V1',
    ], function () {

        /**
         * Registration
         */
        Route::post('/register', 'AuthController@register');

        Route::group(['middleware' => ['auth:api']], function() {

            /**
             * Containers
             */
            Route::apiResource('containers', 'ContainerController');

            /**
             * Measures
             */
            Route::apiResource(
                'containers.measures',
                'MeasureController',
                ['except' => ['update', 'destroy']]
            );

            /**
             * Profile
             */
            Route::get('/me', 'MeController@me');

            /**
             * Stats
             */
            Route::get('/stats/{container}/day', 'StatController@day');
            Route::get('/stats/{container}/week','StatController@week');
            Route::get('/stats/{container}/month','StatController@month');
            Route::get('/stats/{container}/year','StatController@year');

        });

        Route::group(['middleware' => ['client']], function() {
            Route::post('/particle/{device}', 'MeasureController@particle');
        });

    });
