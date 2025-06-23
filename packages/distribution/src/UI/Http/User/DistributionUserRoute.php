<?php

namespace RedJasmine\Distribution\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Distribution\UI\Http\User\Api\Controllers\PromoterBindUserController;
use RedJasmine\Distribution\UI\Http\User\Api\Controllers\PromoterApplyController;
use RedJasmine\Distribution\UI\Http\User\Api\Controllers\PromoterController;

class DistributionUserRoute
{
    /**
     * API 路由
     */
    public static function api(): void
    {
        Route::apiResource('promoter-bind-users', PromoterBindUserController::class)
            ->names('distribution.user.api.promoter-bind-users')
            ->only(['index', 'store']);


            
        Route::apiResource('promoters/applies', PromoterApplyController::class)->names('distribution.api.user.promoter-applies')->only(['index', 'show']);
        Route::get('promoters/info', [PromoterController::class, 'info']);
        Route::post('promoters/register', [PromoterController::class, 'register']);
        Route::post('promoters/upgrade', [PromoterController::class, 'upgrade']);
        Route::post('promoters/test', [PromoterController::class, 'test']);

    }

    /**
     * Web 路由
     */
    public static function web(): void
    {
        // Web 路由可以后续添加
    }
} 