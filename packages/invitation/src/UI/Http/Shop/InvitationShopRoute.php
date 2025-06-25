<?php

namespace RedJasmine\Invitation\UI\Http\Shop;

use Illuminate\Support\Facades\Route;
use RedJasmine\Invitation\UI\Http\Shop\Api\Controllers\InvitationCodeController;

class InvitationShopRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'invitation'], function () {
            // 商家邀请码管理
            Route::apiResource('codes', InvitationCodeController::class);
            
            // 商家邀请统计
            Route::get('statistics', [InvitationCodeController::class, 'statistics']);
            
            // 商家邀请码使用记录
            Route::get('usage-records', [InvitationCodeController::class, 'usageRecords']);
            
            // 商家邀请码生成
            Route::post('codes/generate', [InvitationCodeController::class, 'generate']);
        });
    }

    public static function web(): void
    {
        // Web路由定义（如果需要）
    }
} 