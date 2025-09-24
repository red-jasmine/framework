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
            Route::get('records/statistic', [InteractionRecordController::class, 'statistic'])->middleware(['auth:owner']);
            Route::post('records/cancel', [InteractionRecordController::class, 'cancel'])->middleware(['auth:owner']);
            Route::apiResource('records', InteractionRecordController::class)
                 ->middleware(['auth:owner']);
        });

    }

}