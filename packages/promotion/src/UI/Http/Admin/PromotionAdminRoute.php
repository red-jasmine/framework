<?php

namespace RedJasmine\Promotion\UI\Http\Admin;

use Illuminate\Support\Facades\Route;
use RedJasmine\Promotion\UI\Http\Admin\Api\Controllers\ActivityController;
use RedJasmine\Promotion\UI\Http\Admin\Api\Controllers\ActivityProductController;

/**
 * 活动管理路由定义
 */
class PromotionAdminRoute
{
    /**
     * 注册API路由
     */
    public static function api(): void
    {
        Route::group(['prefix' => 'promotion', 'as' => 'promotion.'], function () {
            // 活动管理路由
            Route::group(['prefix' => 'activities', 'as' => 'activities.'], function () {
                // 标准CRUD路由
                Route::apiResource('', ActivityController::class)->parameters(['' => 'activity']);
                
                // 活动状态管理路由
                Route::patch('{activity}/publish', [ActivityController::class, 'publish'])->name('publish');
                Route::patch('{activity}/approve', [ActivityController::class, 'approve'])->name('approve');
                Route::patch('{activity}/reject', [ActivityController::class, 'reject'])->name('reject');
                Route::patch('{activity}/start', [ActivityController::class, 'start'])->name('start');
                Route::patch('{activity}/pause', [ActivityController::class, 'pause'])->name('pause');
                Route::patch('{activity}/resume', [ActivityController::class, 'resume'])->name('resume');
                Route::patch('{activity}/end', [ActivityController::class, 'end'])->name('end');
                Route::patch('{activity}/cancel', [ActivityController::class, 'cancel'])->name('cancel');
                
                // 活动操作路由
                Route::post('{activity}/copy', [ActivityController::class, 'copy'])->name('copy');
                Route::get('{activity}/statistics', [ActivityController::class, 'statistics'])->name('statistics');
                
                // 活动商品管理路由
                Route::group(['prefix' => '{activity}/products', 'as' => 'products.'], function () {
                    Route::get('', [ActivityProductController::class, 'index'])->name('index');
                    Route::post('', [ActivityProductController::class, 'store'])->name('store');
                    Route::patch('{product}', [ActivityProductController::class, 'update'])->name('update');
                    Route::delete('{product}', [ActivityProductController::class, 'destroy'])->name('destroy');
                    
                    // 批量操作
                    Route::delete('batch', [ActivityProductController::class, 'batchDestroy'])->name('batch.destroy');
                    Route::patch('batch/status', [ActivityProductController::class, 'batchUpdateStatus'])->name('batch.status');
                    Route::patch('batch/show', [ActivityProductController::class, 'batchUpdateShow'])->name('batch.show');
                });
            });
            
            // 活动类型配置路由（可选）
            Route::get('activity-types', function () {
                return response()->json([
                    'data' => collect(\RedJasmine\Promotion\Domain\Models\Enums\ActivityTypeEnum::cases())
                        ->map(fn($type) => [
                            'value' => $type->value,
                            'label' => $type->label(),
                            'color' => $type->color(),
                            'icon' => $type->getIcon(),
                        ])
                ]);
            })->name('activity-types.index');
            
            // 活动状态配置路由（可选）
            Route::get('activity-statuses', function () {
                return response()->json([
                    'data' => collect(\RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum::cases())
                        ->map(fn($status) => [
                            'value' => $status->value,
                            'label' => $status->label(),
                            'color' => $status->color(),
                            'icon' => $status->getIcon(),
                        ])
                ]);
            })->name('activity-statuses.index');
            
            // 活动配置信息路由
            Route::get('config', function () {
                return response()->json([
                    'data' => config('promotion')
                ]);
            })->name('config');
        });
    }

    /**
     * 注册Web路由
     */
    public static function web(): void
    {
        Route::group(['prefix' => 'promotion', 'as' => 'promotion.'], function () {
            // 活动管理页面路由
            Route::get('activities', function () {
                return view('promotion::admin.activities.index');
            })->name('activities.index');
            
            Route::get('activities/create', function () {
                return view('promotion::admin.activities.create');
            })->name('activities.create');
            
            Route::get('activities/{activity}', function ($activity) {
                return view('promotion::admin.activities.show', compact('activity'));
            })->name('activities.show');
            
            Route::get('activities/{activity}/edit', function ($activity) {
                return view('promotion::admin.activities.edit', compact('activity'));
            })->name('activities.edit');
            
            // 活动商品管理页面路由
            Route::get('activities/{activity}/products', function ($activity) {
                return view('promotion::admin.activities.products', compact('activity'));
            })->name('activities.products');
            
            // 活动统计页面路由
            Route::get('activities/{activity}/statistics', function ($activity) {
                return view('promotion::admin.activities.statistics', compact('activity'));
            })->name('activities.statistics');
        });
    }
}
