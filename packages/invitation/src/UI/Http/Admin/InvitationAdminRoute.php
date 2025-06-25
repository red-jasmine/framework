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
            
            // 邀请码统计和分析
            Route::get('statistics', [InvitationCodeController::class, 'statistics']);
            Route::get('analytics', [InvitationCodeController::class, 'analytics']);
            
            // 批量操作
            Route::post('codes/batch-delete', [InvitationCodeController::class, 'batchDelete']);
            Route::post('codes/batch-update', [InvitationCodeController::class, 'batchUpdate']);
        });
    }

    public static function web(): void
    {
        // Web路由定义（如果需要）
    }
} 