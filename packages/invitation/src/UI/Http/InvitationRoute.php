<?php

namespace RedJasmine\Invitation\UI\Http;

use Illuminate\Support\Facades\Route;
use RedJasmine\Invitation\UI\Http\Admin\InvitationAdminRoute;
use RedJasmine\Invitation\UI\Http\User\InvitationUserRoute;

/**
 * 邀请模块路由注册类
 *
 * 统一管理邀请模块的所有路由注册
 */
class InvitationRoute
{
    /**
     * 注册所有路由
     */
    public static function registerAllRoutes() : void
    {
        self::registerApiRoutes();
        self::registerWebRoutes();
    }

    /**
     * 注册所有API路由
     */
    public static function registerApiRoutes() : void
    {
        Route::prefix('api')->middleware(['api'])->group(function () {

            // 管理员路由
            Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
                InvitationAdminRoute::api();
            });
            // 用户路由
            Route::prefix('user')->middleware(['auth:user'])->group(function () {
                InvitationUserRoute::api();
            });


        });


    }

    /**
     * 注册所有Web路由
     */
    public static function registerWebRoutes() : void
    {
        // 管理员Web路由
        Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
            InvitationAdminRoute::web();
        });

        // 用户Web路由
        Route::prefix('user')->middleware(['auth:user'])->group(function () {
            InvitationUserRoute::web();
        });


    }
} 