<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\UI\Http\Shop\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Coupon\Domain\Models\CouponUsage as Model;
use RedJasmine\Coupon\Domain\Repositories\CouponUsageReadRepositoryInterface;
use RedJasmine\Coupon\UI\Http\Shop\Api\Resources\CouponUsageResource as Resource;
use RedJasmine\Coupon\Application\Services\CouponUsage\Queries\CouponUsagePaginateQuery;
use RedJasmine\Coupon\Application\Services\CouponUsage\Queries\CouponUsageFindQuery;

/**
 * 优惠券使用记录控制器（商家端）
 * 
 * 提供商家查看优惠券使用记录的接口，包括：
 * - 查看使用记录列表
 * - 查看使用记录详情
 * - 查看使用记录统计
 */
class CouponUsageController extends Controller
{
    public function __construct(
        protected CouponUsageReadRepositoryInterface $readRepository,
    ) {
        // 商家端查看优惠券使用记录，需要限制查看权限
        $this->readRepository->withQuery(function ($query) {
            // 根据业务需要，可以添加商家相关的查询限制
            // 例如：只查看与当前商家相关的使用记录
            $query->where('cost_bearer_type', $this->getOwner()?->type ?? '')
                  ->where('cost_bearer_id', $this->getOwner()?->id ?? 0);
        });
    }

    /**
     * 获取使用记录列表
     * 
     * 支持通过查询参数筛选：
     * - filter[coupon_id]: 按优惠券ID筛选
     * - filter[order_no]: 按订单号筛选
     * - filter[user_id]: 按用户ID筛选
     * - filter[used_at_between]: 按使用时间范围筛选
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = CouponUsagePaginateQuery::from($request->all());
        
        $result = $this->readRepository->paginate($query);
        
        return Resource::collection($result)->response();
    }

    /**
     * 获取使用记录详情
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $query = CouponUsageFindQuery::from(['id' => $id]);
        $result = $this->readRepository->find($query);
        
        if (!$result) {
            return response()->json(['message' => '使用记录不存在'], 404);
        }
        
        return (new Resource($result))->response();
    }

    /**
     * 获取使用记录统计信息
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function statistics(Request $request): JsonResponse
    {
        $ownerId = $this->getOwner()?->id ?? 0;
        $ownerType = $this->getOwner()?->type ?? '';

        // 基础查询条件
        $baseQuery = Model::where('cost_bearer_type', $ownerType)
                          ->where('cost_bearer_id', $ownerId);

        // 可选的用户筛选
        if ($request->has('user_id')) {
            $baseQuery->where('user_id', $request->get('user_id'));
        }

        if ($request->has('user_type')) {
            $baseQuery->where('user_type', $request->get('user_type'));
        }

        // 可选的优惠券筛选
        if ($request->has('coupon_id')) {
            $baseQuery->where('coupon_id', $request->get('coupon_id'));
        }

        // 可选的时间范围筛选
        if ($request->has('date_range')) {
            $dateRange = $request->get('date_range');
            if (is_array($dateRange) && count($dateRange) == 2) {
                $baseQuery->whereBetween('used_at', $dateRange);
            }
        }

        $statistics = [
            'total_count' => (clone $baseQuery)->count(),
            'total_discount_amount' => (clone $baseQuery)->sum('discount_amount') ?: 0,
            'total_final_discount_amount' => (clone $baseQuery)->sum('final_discount_amount') ?: 0,
            'average_discount_amount' => round((clone $baseQuery)->avg('discount_amount') ?: 0, 2),
            'max_discount_amount' => (clone $baseQuery)->max('discount_amount') ?: 0,
            'min_discount_amount' => (clone $baseQuery)->min('discount_amount') ?: 0,
        ];

        // 按时间统计
        $timeRange = $request->get('time_range', '30'); // 默认30天
        $startDate = now()->subDays((int)$timeRange)->startOfDay();
        $endDate = now()->endOfDay();

        $timeStatistics = [
            'period_count' => (clone $baseQuery)
                                      ->whereBetween('used_at', [$startDate, $endDate])
                                      ->count(),
            'period_discount_amount' => (clone $baseQuery)
                                                 ->whereBetween('used_at', [$startDate, $endDate])
                                                 ->sum('final_discount_amount') ?: 0,
        ];

        // 按月统计（最近12个月）
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            
            $monthlyStats[] = [
                'month' => $monthStart->format('Y-m'),
                'count' => (clone $baseQuery)
                                   ->whereBetween('used_at', [$monthStart, $monthEnd])
                                   ->count(),
                'discount_amount' => (clone $baseQuery)
                                              ->whereBetween('used_at', [$monthStart, $monthEnd])
                                              ->sum('final_discount_amount') ?: 0,
            ];
        }

        // 按日期统计（最近30天）
        $dailyStats = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyStats[] = [
                'date' => $date->format('Y-m-d'),
                'count' => (clone $baseQuery)
                    ->whereDate('used_at', $date)
                    ->count(),
                'discount_amount' => (clone $baseQuery)
                    ->whereDate('used_at', $date)
                    ->sum('final_discount_amount') ?: 0,
            ];
        }

        return response()->json([
            'data' => [
                'overall' => $statistics,
                'time_range' => array_merge($timeStatistics, [
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'days' => $timeRange,
                ]),
                'monthly' => $monthlyStats,
                'daily' => $dailyStats,
            ]
        ]);
    }

    /**
     * 权限验证
     * 
     * @param string $ability
     * @param array $arguments
     * @return bool
     */
    public function authorize($ability, $arguments = []): bool
    {
        // 商家端权限验证
        return true;
    }
} 