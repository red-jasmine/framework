<?php

namespace RedJasmine\Distribution\UI\Http\Admin;

use Illuminate\Support\Facades\Route;
use RedJasmine\Distribution\UI\Http\Admin\Api\Controllers\PromoterController;
use RedJasmine\Distribution\UI\Http\Admin\Api\Controllers\PromoterApplyController;
use RedJasmine\Distribution\UI\Http\Admin\Api\Controllers\PromoterTeamController;
use RedJasmine\Distribution\UI\Http\Admin\Api\Controllers\PromoterLevelController;

class PromoterAdminRoute
{
    /**
     * API 路由
     */
    public static function api(): void
    {
        // 分销员管理
        Route::prefix('promoters')->group(function () {
            Route::get('/', [PromoterController::class, 'index'])->name('admin.distribution.promoters.index');
            Route::get('/{id}', [PromoterController::class, 'show'])->name('admin.distribution.promoters.show');
            Route::post('/{id}/upgrade', [PromoterController::class, 'upgrade'])->name('admin.distribution.promoters.upgrade');
            Route::post('/{id}/downgrade', [PromoterController::class, 'downgrade'])->name('admin.distribution.promoters.downgrade');
            Route::post('/{id}/enable', [PromoterController::class, 'enable'])->name('admin.distribution.promoters.enable');
            Route::post('/{id}/disable', [PromoterController::class, 'disable'])->name('admin.distribution.promoters.disable');
            Route::post('/{id}/set-parent', [PromoterController::class, 'setParent'])->name('admin.distribution.promoters.set-parent');
        });

        // 分销申请管理
        Route::prefix('promoter-applies')->group(function () {
            Route::get('/', [PromoterApplyController::class, 'index'])->name('admin.distribution.promoter-applies.index');
            Route::get('/{id}', [PromoterApplyController::class, 'show'])->name('admin.distribution.promoter-applies.show');
            Route::post('/{id}/approval', [PromoterApplyController::class, 'approval'])->name('admin.distribution.promoter-applies.approval');
            Route::post('/{id}/reject', [PromoterApplyController::class, 'reject'])->name('admin.distribution.promoter-applies.reject');
            Route::post('/{id}/revoke', [PromoterApplyController::class, 'revoke'])->name('admin.distribution.promoter-applies.revoke');
        });

        // 分销团队管理
        Route::apiResource('promoter-teams', PromoterTeamController::class)->names('admin.distribution.promoter-teams');

        // 分销等级管理
        Route::apiResource('promoter-levels', PromoterLevelController::class)->names('admin.distribution.promoter-levels');

        // 分销分组管理 - 待实现PromoterGroupController
        // Route::prefix('promoter-groups')->group(function () {
        //     Route::get('/', [PromoterGroupController::class, 'index'])->name('admin.distribution.promoter-groups.index');
        //     Route::post('/', [PromoterGroupController::class, 'store'])->name('admin.distribution.promoter-groups.store');
        //     Route::get('/{id}', [PromoterGroupController::class, 'show'])->name('admin.distribution.promoter-groups.show');
        //     Route::put('/{id}', [PromoterGroupController::class, 'update'])->name('admin.distribution.promoter-groups.update');
        //     Route::delete('/{id}', [PromoterGroupController::class, 'destroy'])->name('admin.distribution.promoter-groups.destroy');
        //     Route::get('/tree', [PromoterGroupController::class, 'tree'])->name('admin.distribution.promoter-groups.tree');
        // });
    }

    /**
     * Web 路由
     */
    public static function web(): void
    {
        // 暂时为空，可根据需要添加Web路由
    }
}