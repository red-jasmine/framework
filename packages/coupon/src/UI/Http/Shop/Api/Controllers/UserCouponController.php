<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\UI\Http\Shop\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponPaginateQuery as PaginateQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponFindQuery as FindQuery;
use RedJasmine\Coupon\Domain\Data\UserCouponData as Data;
use RedJasmine\Coupon\Domain\Models\UserCoupon as Model;
use RedJasmine\Coupon\UI\Http\Shop\Api\Resources\UserCouponResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 用户优惠券控制器（商家端）
 * 
 * 提供商家查看用户领取优惠券的接口，包括：
 * - 查看用户优惠券列表
 * - 查看用户优惠券详情
 * - 查看用户优惠券统计
 */
class UserCouponController extends Controller
{
    use RestControllerActions;

    protected static string $resourceClass = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $findQueryClass = FindQuery::class;
    protected static string $modelClass = Model::class;
    protected static string $dataClass = Data::class;

    public function __construct(
        protected UserCouponApplicationService $service,
    ) {
        // 商家端查看用户优惠券，需要限制查看权限
        $this->service->readRepository->withQuery(function ($query) {
            // 根据业务需要，可以添加商家相关的查询限制
            // 例如：只查看与当前商家相关的优惠券
            $query->where('owner_type', $this->getOwner()?->type ?? '')
                  ->where('owner_id', $this->getOwner()?->id ?? 0);
        });
    }

    /**
     * 获取用户优惠券列表
     * 
     * 商家可以查看用户领取的优惠券列表，支持通过status参数过滤不同状态的优惠券:
     * - available: 可用优惠券
     * - used: 已使用优惠券
     * - expired: 已过期优惠券
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = PaginateQuery::from($request->all());
        
        // 根据status参数添加相应的过滤条件
        if ($request->has('status')) {
            $this->service->readRepository->withQuery(function ($queryBuilder) use ($request) {
                $status = $request->get('status');
                
                switch ($status) {
                    case 'available':
                        $queryBuilder->available();
                        break;
                    case 'used':
                        $queryBuilder->used();
                        break;
                    case 'expired':
                        $queryBuilder->expired();
                        break;
                    default:
                        // 不添加额外过滤，显示所有状态的优惠券
                        break;
                }
            });
        }

        $result = $this->service->paginate($query);
        
        return Resource::collection($result)->response();
    }

    /**
     * 获取用户优惠券详情
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $query = FindQuery::from(['id' => $id]);
        $result = $this->service->find($query);
        
        if (!$result) {
            return response()->json(['message' => '用户优惠券不存在'], 404);
        }
        
        return (new Resource($result))->response();
    }

    /**
     * 获取用户优惠券统计信息
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function statistics(Request $request): JsonResponse
    {
        $ownerId = $this->getOwner()?->id ?? 0;
        $ownerType = $this->getOwner()?->type ?? '';

        // 基础查询条件
        $baseQuery = Model::where('owner_type', $ownerType)
                          ->where('owner_id', $ownerId);

        // 可选的用户筛选
        if ($request->has('user_id')) {
            $baseQuery->where('user_id', $request->get('user_id'));
        }

        if ($request->has('user_type')) {
            $baseQuery->where('user_type', $request->get('user_type'));
        }

        // 可选的时间范围筛选
        if ($request->has('date_range')) {
            $dateRange = $request->get('date_range');
            if (is_array($dateRange) && count($dateRange) == 2) {
                $baseQuery->whereBetween('created_at', $dateRange);
            }
        }

        $statistics = [
            'total' => (clone $baseQuery)->count(),
            'available' => (clone $baseQuery)->available()->count(),
            'used' => (clone $baseQuery)->used()->count(),
            'expired' => (clone $baseQuery)->expired()->count(),
        ];

        // 按日期统计（最近30天）
        $dailyStats = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dailyStats[] = [
                'date' => $date,
                'count' => (clone $baseQuery)
                    ->whereDate('created_at', $date)
                    ->count(),
                'used_count' => (clone $baseQuery)
                    ->whereDate('used_time', $date)
                    ->count(),
            ];
        }

        return response()->json([
            'data' => [
                'overview' => $statistics,
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