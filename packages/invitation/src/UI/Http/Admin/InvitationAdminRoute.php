<?php

namespace RedJasmine\Invitation\UI\Http\Admin;

use Illuminate\Support\Facades\Route;
use RedJasmine\Invitation\UI\Http\Admin\Api\Controllers\InvitationCodeController;

class InvitationAdminRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'invitation'], function () {
            // 邀请码管理
            Route::apiResource('codes', InvitationCodeController::class);
            

        });
    }

    public static function web(): void
    {
        // Web路由定义（如果需要）
    }
} 