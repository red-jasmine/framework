<?php

namespace RedJasmine\Community\UI\Http\User;

use Illuminate\Support\Facades\Route;
use RedJasmine\Community\UI\Http\User\Api\Controllers\TopicCategoryController;
use RedJasmine\Community\UI\Http\User\Api\Controllers\TopicController;

class CommunityUserRoute
{

    public static function api() : void
    {
        Route::group(['prefix' => 'community'], function () {
            Route::apiResource('topics', TopicController::class)->middleware('auth:owner');
            Route::get('categories/tree', [TopicCategoryController::class, 'tree']);
            Route::apiResource('categories', TopicCategoryController::class);
        });

    }

}