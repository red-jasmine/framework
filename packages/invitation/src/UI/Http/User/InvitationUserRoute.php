<?php

namespace RedJasmine\Invitation\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Invitation\UI\Http\User\Api\Controllers\InvitationCodeController;

class InvitationUserRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'invitation'], function () {
            // 邀请码基础CRUD操作
            Route::apiResource('codes', InvitationCodeController::class);
            
            // 邀请码使用相关
            Route::post('codes/use', [InvitationCodeController::class, 'use']);
            Route::post('codes/generate-url', [InvitationCodeController::class, 'generateUrl']);
            
            // 邀请统计
            Route::get('statistics', [InvitationCodeController::class, 'statistics']);
        });
    }

    public static function web(): void
    {
        // Web路由定义（如果需要）
    }
} 