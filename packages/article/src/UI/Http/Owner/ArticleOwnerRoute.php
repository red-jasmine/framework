<?php

namespace RedJasmine\Article\UI\Http\Owner;

use Illuminate\Support\Facades\Route;
use RedJasmine\Article\UI\Http\Owner\Api\Controllers\ArticleCategoryController;
use RedJasmine\Article\UI\Http\Owner\Api\Controllers\ArticleController;
use RedJasmine\Article\UI\Http\Owner\Api\Controllers\ArticleTagController;

/**
 * 文章 Owner 端路由定义
 */
class ArticleOwnerRoute
{
    /**
     * API 路由
     */
    public static function api(): void
    {
        Route::group(['prefix' => 'article', 'middleware' => ['api']], function () {
            // 文章分类路由
            Route::group(['prefix' => 'categories'], function () {
                Route::get('tree', [ArticleCategoryController::class, 'tree']);
                Route::apiResource('', ArticleCategoryController::class)->parameters(['' => 'article_category']);
            });

            // 文章标签路由
            Route::apiResource('tags', ArticleTagController::class)->parameters(['tags' => 'article_tag']);

            // 文章路由
            Route::group(['prefix' => 'articles'], function () {
                Route::post('{article}/publish', [ArticleController::class, 'publish']);
                Route::post('{article}/unpublish', [ArticleController::class, 'unpublish']);
            });
            Route::apiResource('articles', ArticleController::class);
        });
    }

    /**
     * Web 路由
     */
    public static function web(): void
    {
        Route::group(['prefix' => 'article', 'middleware' => ['web']], function () {
            // 文章分类路由
            Route::group(['prefix' => 'categories'], function () {
                Route::get('tree', [ArticleCategoryController::class, 'tree']);
                Route::resource('', ArticleCategoryController::class)->parameters(['' => 'article_category']);
            });

            // 文章标签路由
            Route::resource('tags', ArticleTagController::class)->parameters(['tags' => 'article_tag']);

            // 文章路由
            Route::group(['prefix' => 'articles'], function () {
                Route::post('{article}/publish', [ArticleController::class, 'publish']);
                Route::post('{article}/unpublish', [ArticleController::class, 'unpublish']);
            });
            Route::resource('articles', ArticleController::class);
        });
    }
}
