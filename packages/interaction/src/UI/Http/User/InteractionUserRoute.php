<?php

namespace RedJasmine\Interaction\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Interaction\UI\Http\User\Api\Controllers\InteractionController;
use RedJasmine\Interaction\UI\Http\User\Api\Controllers\InteractionRecordController;

class InteractionUserRoute
{


    public static function api() : void
    {
        Route::group(['prefix' => 'interaction'], function () {
            Route::post('interactive', [InteractionController::class, 'interactive'])->middleware(['auth:api']);

            Route::apiResource('records', InteractionRecordController::class);
        });

    }

}