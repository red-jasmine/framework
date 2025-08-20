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
                Route::get('/statistics', [MessageController::class, 'statistics']); // 未读数量
                Route::get('/statistics/unread', [MessageController::class, 'unreadCount']); // 未读数量
            });


            Route::get('categories/tree', [MessageCategoryController::class, 'tree']);
            Route::apiResource('categories', MessageCategoryController::class)->only(['index', 'show']);


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
