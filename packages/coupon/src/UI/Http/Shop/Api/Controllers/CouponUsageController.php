<?php

declare(strict_types = 1);

namespace RedJasmine\Coupon\UI\Http\Shop\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Coupon\Application\Services\CouponUsage\CouponUsageApplicationService;
use RedJasmine\Coupon\Domain\Models\CouponUsage as Model;
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
        protected CouponUsageApplicationService $service,
    ) {
        // 商家端查看优惠券使用记录，需要限制查看权限
        $this->service->repository->withQuery(function ($query) {
            // 根据业务需要，可以添加商家相关的查询限制
            // 例如：只查看与当前商家相关的使用记录
            $query->onlyOwner($this->getOwner());
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
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $query = CouponUsagePaginateQuery::from($request->all());

        $result = $this->service->repository->paginate($query);

        return Resource::collection($result)->response();
    }

    /**
     * 获取使用记录详情
     *
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function show(int $id) : JsonResponse
    {
        $query  = CouponUsageFindQuery::from(['id' => $id]);
        $result = $this->service->repository->find($query);

        if (!$result) {
            return response()->json(['message' => '使用记录不存在'], 404);
        }

        return new Resource($result);
    }

}
