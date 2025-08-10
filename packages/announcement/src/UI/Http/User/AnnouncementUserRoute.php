<?php

namespace RedJasmine\Announcement\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Announcement\UI\Http\User\Api\Controllers\AnnouncementController;
use RedJasmine\Announcement\UI\Http\User\Api\Controllers\CategoryController;

class AnnouncementUserRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'announcement'], function () {
            // 公告查看 - 用户只能查看已发布的公告
            Route::get('announcements', [AnnouncementController::class, 'index']);
            Route::get('announcements/{id}', [AnnouncementController::class, 'show']);
            
            // 分类查看 - 用户只能查看显示的分类
            Route::get('categories/tree', [CategoryController::class, 'tree']);
            Route::get('categories', [CategoryController::class, 'index']);
            Route::get('categories/{id}', [CategoryController::class, 'show']);

        });
    }

    public static function web(): void
    {
        Route::group(['prefix' => 'announcement'], function () {
            // Web端公告查看
            Route::get('announcements', [AnnouncementController::class, 'index']);
            Route::get('announcements/{id}', [AnnouncementController::class, 'show']);
            
            // Web端分类查看
            Route::get('categories', [CategoryController::class, 'index']);
            Route::get('categories/{id}', [CategoryController::class, 'show']);
            Route::get('categories/tree', [CategoryController::class, 'tree']);
        });
    }
}
