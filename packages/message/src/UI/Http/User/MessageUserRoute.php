<?php

declare(strict_types = 1);

namespace RedJasmine\Message\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Message\UI\Http\User\Api\Controllers\MessageCategoryController;
use RedJasmine\Message\UI\Http\User\Api\Controllers\MessageController;
use RedJasmine\Message\UI\Http\User\Api\Controllers\MessageTemplateController;

/**
 * 消息用户端路由
 */
class MessageUserRoute
{
    /**
     * API 路由
     */
    public static function api() : void
    {
        Route::group(['prefix' => 'message'], function () {


            // 消息路由
            Route::group(['prefix' => 'messages'], function () {
                Route::post('/all-read', [MessageController::class, 'allMarkAsRead']); // 全部标记已读
                Route::apiResource('/', MessageController::class)->only(['index', 'show']);
                Route::patch('{id}/read', [MessageController::class, 'read']); // 批量标记已读
                Route::get('/statistics/unread-count', [MessageController::class, 'unreadCount']); // 未读数量
            });


            Route::get('categories/tree', [MessageCategoryController::class, 'tree']);
            Route::apiResource('categories', MessageCategoryController::class)->only(['index', 'show']);


            // 消息模板路由
            Route::group(['prefix' => 'templates'], function () {
                // 基础 CRUD
                Route::get('/', [MessageTemplateController::class, 'index']); // 模板列表
                Route::post('/', [MessageTemplateController::class, 'store']); // 创建模板
                Route::get('/{id}', [MessageTemplateController::class, 'show']); // 模板详情
                Route::put('/{id}', [MessageTemplateController::class, 'update']); // 更新模板
                Route::delete('/{id}', [MessageTemplateController::class, 'destroy']); // 删除模板

                // 特殊查询
                Route::get('/enabled/list', [MessageTemplateController::class, 'enabled']); // 启用的模板
                Route::get('/popular/{limit?}', [MessageTemplateController::class, 'popular']); // 热门模板
                Route::get('/biz/{biz}', [MessageTemplateController::class, 'byBiz']); // 按业务线
                Route::get('/category/{categoryId}', [MessageTemplateController::class, 'byCategory']); // 按分类
                Route::get('/type/{type}', [MessageTemplateController::class, 'byType']); // 按类型
                Route::get('/code/{code}', [MessageTemplateController::class, 'byCode']); // 按编码
                Route::get('/search/{keyword}', [MessageTemplateController::class, 'search']); // 搜索模板

                // 模板操作
                Route::post('/{id}/duplicate', [MessageTemplateController::class, 'duplicate']); // 复制模板
                Route::post('/{id}/preview', [MessageTemplateController::class, 'preview']); // 预览模板

                // 验证和统计
                Route::get('/check-name/{name}', [MessageTemplateController::class, 'checkName']); // 检查名称
                Route::get('/statistics/usage', [MessageTemplateController::class, 'statistics']); // 使用统计
                Route::get('/statistics/variables', [MessageTemplateController::class, 'variableStatistics']); // 变量统计
            });
        });
    }

    /**
     * Web 路由
     */
    public static function web() : void
    {
        Route::group(['prefix' => 'message', 'middleware' => ['web', 'auth']], function () {

            // 消息页面
            Route::get('/', function () {
                return view('message::user.index');
            })->name('message.user.index');

            Route::get('/messages', function () {
                return view('message::user.messages.index');
            })->name('message.user.messages.index');

            Route::get('/messages/{id}', function ($id) {
                return view('message::user.messages.show', compact('id'));
            })->name('message.user.messages.show');

            // 分类页面
            Route::get('/categories', function () {
                return view('message::user.categories.index');
            })->name('message.user.categories.index');

            // 模板页面
            Route::get('/templates', function () {
                return view('message::user.templates.index');
            })->name('message.user.templates.index');

            Route::get('/templates/{id}', function ($id) {
                return view('message::user.templates.show', compact('id'));
            })->name('message.user.templates.show');
        });
    }
}
