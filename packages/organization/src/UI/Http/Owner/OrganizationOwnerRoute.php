<?php

namespace RedJasmine\Organization\UI\Http\Owner;

use Illuminate\Support\Facades\Route;

class OrganizationOwnerRoute
{
    public static function api(): void
    {
        Route::group(['prefix' => 'organization', 'middleware' => ['api']], function () {
            // 成员管理
            Route::apiResource('members', \RedJasmine\Organization\UI\Http\Owner\Api\Controllers\MemberController::class);

            // 部门管理
            Route::apiResource('departments', \RedJasmine\Organization\UI\Http\Owner\Api\Controllers\DepartmentController::class);
            Route::get('departments/tree', [\RedJasmine\Organization\UI\Http\Owner\Api\Controllers\DepartmentController::class, 'tree']);

            // 职位管理
            Route::apiResource('positions', \RedJasmine\Organization\UI\Http\Owner\Api\Controllers\PositionController::class);

            // 组织管理
            Route::apiResource('organizations', \RedJasmine\Organization\UI\Http\Owner\Api\Controllers\OrganizationController::class);

            // 部门管理员管理
            Route::apiResource('department-managers', \RedJasmine\Organization\UI\Http\Owner\Api\Controllers\DepartmentManagerController::class);

            // 成员部门关系管理
            Route::apiResource('member-departments', \RedJasmine\Organization\UI\Http\Owner\Api\Controllers\MemberDepartmentController::class);
        });
    }

    public static function web(): void
    {
        Route::group(['prefix' => 'organization', 'middleware' => ['web']], function () {
            // 成员管理
            Route::get('members', [\RedJasmine\Organization\UI\Http\Owner\Api\Controllers\MemberController::class, 'index']);
            Route::get('members/{id}', [\RedJasmine\Organization\UI\Http\Owner\Api\Controllers\MemberController::class, 'show']);

            // 部门管理
            Route::get('departments', [\RedJasmine\Organization\UI\Http\Owner\Api\Controllers\DepartmentController::class, 'index']);
            Route::get('departments/{id}', [\RedJasmine\Organization\UI\Http\Owner\Api\Controllers\DepartmentController::class, 'show']);
            Route::get('departments/tree', [\RedJasmine\Organization\UI\Http\Owner\Api\Controllers\DepartmentController::class, 'tree']);

            // 职位管理
            Route::get('positions', [\RedJasmine\Organization\UI\Http\Owner\Api\Controllers\PositionController::class, 'index']);
            Route::get('positions/{id}', [\RedJasmine\Organization\UI\Http\Owner\Api\Controllers\PositionController::class, 'show']);
        });
    }
}
