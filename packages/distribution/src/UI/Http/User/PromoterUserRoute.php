<?php

namespace RedJasmine\Distribution\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Distribution\UI\Http\User\Api\Controllers\PromoterController;

class PromoterUserRoute
{

    public static function api() : void
    {
        Route::group(['prefix' => 'distribution'], function () {
            Route::apiResource('promoters', PromoterController::class);
            Route::post('promoters/apply', [PromoterController::class, 'apply']);
        });

    }

}