<?php

namespace RedJasmine\Distribution\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Distribution\UI\Http\User\Api\Controllers\PromoterBindUserController;

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
    }

    /**
     * Web 路由
     */
    public static function web(): void
    {
        // Web 路由可以后续添加
    }
} 