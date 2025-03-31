<?php

namespace RedJasmine\Article\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Article\UI\Http\User\Api\Controllers\TopicCategoryController;
use RedJasmine\Community\UI\Http\User\Api\Controllers\TopicController;

class CommunityUserRoute
{

    public static function api() : void
    {
        Route::group(['prefix' => 'community'], function () {
            Route::apiResource('topics', TopicController::class);
            Route::get('categories/tree', [TopicCategoryController::class, 'tree']);
            Route::apiResource('categories', TopicCategoryController::class);
        });

    }

}