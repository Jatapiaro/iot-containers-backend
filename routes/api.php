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
             * Profile
             */
            Route::get('/me', 'MeController@me');
        });

    });
