<?php

namespace RedJasmine\Announcement\UI\Http\Owner;

use Illuminate\Support\Facades\Route;
use RedJasmine\Announcement\UI\Http\Owner\Api\Controllers\AnnouncementController;
use RedJasmine\Announcement\UI\Http\Owner\Api\Controllers\CategoryController;

class AnnouncementOwnerRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'announcement'], function () {
            // 公告管理 - Owner 端完整 CRUD 操作
            Route::apiResource('announcements', AnnouncementController::class);

            // 公告状态操作
            Route::patch('announcements/{id}/publish', [AnnouncementController::class, 'publish']);
            Route::patch('announcements/{id}/revoke', [AnnouncementController::class, 'revoke']);
            Route::patch('announcements/{id}/submit-approval', [AnnouncementController::class, 'submitApproval']);
            Route::patch('announcements/{id}/approve', [AnnouncementController::class, 'approve']);
            Route::patch('announcements/{id}/reject', [AnnouncementController::class, 'reject']);

            // 分类管理 - Owner 端完整 CRUD 操作
            Route::apiResource('categories', CategoryController::class);

            // 分类状态操作
            Route::patch('categories/{id}/show', [CategoryController::class, 'show']);
            Route::patch('categories/{id}/hide', [CategoryController::class, 'hide']);
            Route::patch('categories/{id}/move', [CategoryController::class, 'move']);

            // 分类树形结构
            Route::get('categories/tree', [CategoryController::class, 'tree']);
        });
    }

    public static function web(): void
    {
        Route::group(['prefix' => 'announcement'], function () {
            // Web端公告管理
            Route::get('announcements', [AnnouncementController::class, 'index']);
            Route::get('announcements/create', [AnnouncementController::class, 'create']);
            Route::post('announcements', [AnnouncementController::class, 'store']);
            Route::get('announcements/{id}', [AnnouncementController::class, 'show']);
            Route::get('announcements/{id}/edit', [AnnouncementController::class, 'edit']);
            Route::put('announcements/{id}', [AnnouncementController::class, 'update']);
            Route::delete('announcements/{id}', [AnnouncementController::class, 'destroy']);

            // Web端分类管理
            Route::get('categories', [CategoryController::class, 'index']);
            Route::get('categories/create', [CategoryController::class, 'create']);
            Route::post('categories', [CategoryController::class, 'store']);
            Route::get('categories/{id}', [CategoryController::class, 'show']);
            Route::get('categories/{id}/edit', [CategoryController::class, 'edit']);
            Route::put('categories/{id}', [CategoryController::class, 'update']);
            Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
            Route::get('categories/tree', [CategoryController::class, 'tree']);
        });
    }
}
