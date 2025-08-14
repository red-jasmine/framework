<?php

declare(strict_types=1);

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
    public static function api(): void
    {
        Route::group(['prefix' => 'message', 'middleware' => ['auth:sanctum']], function () {
            
            // 消息路由
            Route::group(['prefix' => 'messages'], function () {
                // 基础 CRUD
                Route::get('/', [MessageController::class, 'index']); // 消息列表
                Route::post('/', [MessageController::class, 'store']); // 创建消息
                Route::get('/{id}', [MessageController::class, 'show']); // 消息详情
                
                // 发送消息
                Route::post('/send', [MessageController::class, 'send']); // 发送消息
                
                // 消息操作
                Route::patch('/mark-as-read', [MessageController::class, 'markAsRead']); // 批量标记已读
                Route::patch('/{id}/read', [MessageController::class, 'read']); // 单个标记已读
                Route::patch('/mark-all-as-read', [MessageController::class, 'markAllAsRead']); // 全部标记已读
                Route::patch('/{id}/archive', [MessageController::class, 'archive']); // 归档消息
                Route::patch('/batch-archive', [MessageController::class, 'batchArchive']); // 批量归档
                
                // 统计和查询
                Route::get('/statistics/overview', [MessageController::class, 'statistics']); // 消息统计
                Route::get('/statistics/unread-count', [MessageController::class, 'unreadCount']); // 未读数量
                Route::get('/high-priority/unread', [MessageController::class, 'highPriorityUnread']); // 高优先级未读
            });

            // 消息分类路由
            Route::group(['prefix' => 'categories'], function () {
                // 基础 CRUD
                Route::get('/', [MessageCategoryController::class, 'index']); // 分类列表
                Route::post('/', [MessageCategoryController::class, 'store']); // 创建分类
                Route::get('/{id}', [MessageCategoryController::class, 'show']); // 分类详情
                Route::put('/{id}', [MessageCategoryController::class, 'update']); // 更新分类
                Route::delete('/{id}', [MessageCategoryController::class, 'destroy']); // 删除分类
                
                // 特殊查询
                Route::get('/tree/all', [MessageCategoryController::class, 'tree']); // 分类树
                Route::get('/enabled/list', [MessageCategoryController::class, 'enabled']); // 启用的分类
                Route::get('/search/{keyword}', [MessageCategoryController::class, 'search']); // 搜索分类
                Route::get('/{id}/path', [MessageCategoryController::class, 'path']); // 分类路径
                
                // 验证和统计
                Route::get('/check-name/{name}', [MessageCategoryController::class, 'checkName']); // 检查名称
                Route::get('/statistics/usage', [MessageCategoryController::class, 'statistics']); // 使用统计
                
                // 批量操作
                Route::patch('/batch/enable', [MessageCategoryController::class, 'batchEnable']); // 批量启用
                Route::patch('/batch/disable', [MessageCategoryController::class, 'batchDisable']); // 批量禁用
                Route::patch('/batch/sort', [MessageCategoryController::class, 'updateSort']); // 更新排序
            });

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
    public static function web(): void
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
