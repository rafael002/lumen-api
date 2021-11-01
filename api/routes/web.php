<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** Reset state before starting tests */

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

$router->post('/reset', function () use ($router) {
    try {
        // Clean accounts table
        DB::table('accounts')->truncate();

        return response('OK', ResponseAlias::HTTP_OK);
    } catch (Exception $exception) {
        return response()->json([
            'description' => 'Internal Server Error',
            'error' => $exception->getMessage()
        ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

});


$router->post('event/', ['middleware' => 'event_validation', 'uses' => EventController::class]);


$router->get('balance/', AccountController::class);

