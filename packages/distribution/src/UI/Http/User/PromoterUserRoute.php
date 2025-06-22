<?php

namespace RedJasmine\Distribution\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Distribution\UI\Http\User\Api\Controllers\PromoterApplyController;
use RedJasmine\Distribution\UI\Http\User\Api\Controllers\PromoterController;

class PromoterUserRoute
{

    public static function api() : void
    {

        Route::apiResource('promoters/applies', PromoterApplyController::class)->names('distribution.api.user.promoter-applies')->only(['index', 'show']);
        Route::get('promoters/info', [PromoterController::class, 'info']);
        Route::post('promoters/register', [PromoterController::class, 'register']);
        Route::post('promoters/upgrade', [PromoterController::class, 'upgrade']);
        Route::post('promoters/test', [PromoterController::class, 'test']);

    }

}
