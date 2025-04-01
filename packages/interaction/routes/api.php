<?php


use RedJasmine\Interaction\UI\Http\User\InteractionUserRoute;

\Illuminate\Support\Facades\Route::group([
    'prefix'     => 'api',
    'middleware' => ['api']
], function () {

    InteractionUserRoute::api();

});