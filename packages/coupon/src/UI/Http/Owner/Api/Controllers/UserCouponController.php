<?php

declare(strict_types = 1);

namespace RedJasmine\Coupon\UI\Http\Owner\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponFindQuery as FindQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponPaginateQuery as PaginateQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Models\UserCoupon as Model;
use RedJasmine\Coupon\UI\Http\Owner\Api\Resources\UserCouponResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

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
    use RestQueryControllerActions;

    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $findQueryClass     = FindQuery::class;
    protected static string $modelClass         = Model::class;


    public function __construct(
        protected UserCouponApplicationService $service,
    ) {
        // 商家端查看用户优惠券，需要限制查看权限
        $this->service->repository->withQuery(function ($query) {
            // 根据业务需要，可以添加商家相关的查询限制
            // 例如：只查看与当前商家相关的优惠券
            $query->onlyOwner($this->getOwner());
        });
    }

    /**
     * Authorize a given action for the current user.
     *
     * @param  mixed  $ability
     * @param  mixed|array  $arguments
     *
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorize($ability, $arguments = [])
    {
        return  true;
    }


}
