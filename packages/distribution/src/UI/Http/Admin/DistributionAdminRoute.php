<?php

namespace RedJasmine\Distribution\UI\Http\Admin;

use Illuminate\Support\Facades\Route;
use RedJasmine\Distribution\UI\Http\Admin\Api\Controllers\PromoterBindUserController;

class DistributionAdminRoute
{
    /**
     * API 路由
     */
    public static function api(): void
    {
        Route::apiResource('promoter-bind-users', PromoterBindUserController::class)
            ->names('distribution.admin.api.promoter-bind-users')
            ->only(['index', 'show', 'store', 'destroy']);
    }

    /**
     * Web 路由
     */
    public static function web(): void
    {
        // Web 路由可以后续添加
    }
} 