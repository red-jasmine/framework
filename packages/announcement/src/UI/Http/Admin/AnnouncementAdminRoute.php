<?php

namespace RedJasmine\Announcement\UI\Http\Admin;

use Illuminate\Support\Facades\Route;
use RedJasmine\Announcement\UI\Http\Admin\Api\Controllers\AnnouncementController;
use RedJasmine\Announcement\UI\Http\Admin\Api\Controllers\CategoryController;

class AnnouncementAdminRoute
{
    public static function api() : void
    {
        Route::group(['prefix' => 'announcement'], function () {
            // 公告管理
            Route::apiResource('announcements', AnnouncementController::class);
            Route::patch('announcements/{id}/publish', [AnnouncementController::class, 'publish']);
            Route::patch('announcements/{id}/revoke', [AnnouncementController::class, 'revoke']);
            Route::patch('announcements/{id}/submit-approval', [AnnouncementController::class, 'submitApproval']);
            Route::patch('announcements/{id}/approve', [AnnouncementController::class, 'approve']);
            Route::patch('announcements/{id}/reject', [AnnouncementController::class, 'reject']);

            // 分类管理
            Route::get('categories/tree', [CategoryController::class, 'tree']);
            Route::apiResource('categories', CategoryController::class);

        });
    }

    public static function web() : void
    {
        Route::group(['prefix' => 'announcement'], function () {
            // Web路由（如果需要）
            Route::get('announcements', [AnnouncementController::class, 'index']);
            Route::get('announcements/{id}', [AnnouncementController::class, 'show']);
            Route::get('categories', [CategoryController::class, 'index']);
            Route::get('categories/{id}', [CategoryController::class, 'show']);
        });
    }
}
