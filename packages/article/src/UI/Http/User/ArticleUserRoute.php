<?php

namespace RedJasmine\Article\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Article\UI\Http\User\Api\Controllers\ArticleCategoryController;
use RedJasmine\Article\UI\Http\User\Api\Controllers\ArticleController;

class ArticleUserRoute
{

    public static function api() : void
    {
        Route::group(['prefix' => 'article'], function () {
            Route::apiResource('articles', ArticleController::class);
            Route::get('categories/tree', [ArticleCategoryController::class,'tree']);
            Route::apiResource('categories', ArticleCategoryController::class);
        });

    }

}