<?php

namespace RedJasmine\Community\UI\Http\Owner;

use Illuminate\Support\Facades\Route;
use RedJasmine\Community\UI\Http\Owner\Api\Controllers\TopicCategoryController;
use RedJasmine\Community\UI\Http\Owner\Api\Controllers\TopicController;
use RedJasmine\Community\UI\Http\Owner\Api\Controllers\TopicTagController;

class CommunityOwnerRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'community', 'middleware' => ['auth:owner']], function () {
            // 话题管理
            Route::apiResource('topics', TopicController::class);
            Route::patch('topics/{topic}/publish', [TopicController::class, 'publish']);
            Route::patch('topics/{topic}/draft', [TopicController::class, 'draft']);

            // 分类管理
            Route::get('categories/tree', [TopicCategoryController::class, 'tree']);
            Route::apiResource('categories', TopicCategoryController::class);

            // 标签管理
            Route::apiResource('tags', TopicTagController::class);
        });
    }

    public static function web(): void
    {
        Route::group(['prefix' => 'community', 'middleware' => ['auth:owner']], function () {
            // 话题页面
            Route::get('topics', [TopicController::class, 'index']);
            Route::get('topics/create', [TopicController::class, 'create']);
            Route::get('topics/{topic}', [TopicController::class, 'show']);
            Route::get('topics/{topic}/edit', [TopicController::class, 'edit']);

            // 分类页面
            Route::get('categories', [TopicCategoryController::class, 'index']);
            Route::get('categories/create', [TopicCategoryController::class, 'create']);
            Route::get('categories/{category}', [TopicCategoryController::class, 'show']);
            Route::get('categories/{category}/edit', [TopicCategoryController::class, 'edit']);

            // 标签页面
            Route::get('tags', [TopicTagController::class, 'index']);
            Route::get('tags/create', [TopicTagController::class, 'create']);
            Route::get('tags/{tag}', [TopicTagController::class, 'show']);
            Route::get('tags/{tag}/edit', [TopicTagController::class, 'edit']);
        });
    }
}
