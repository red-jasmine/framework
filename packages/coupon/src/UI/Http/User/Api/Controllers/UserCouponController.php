<?php

declare(strict_types = 1);

namespace RedJasmine\Coupon\UI\Http\User\Api\Controllers;

use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponFindQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponPaginateQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Models\UserCoupon as Model;
use RedJasmine\Coupon\UI\Http\User\Api\Resources\UserCouponResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

/**
 * 用户优惠券控制器
 *
 * 负责用户已领取优惠券的查询功能
 */
class UserCouponController extends Controller
{
    protected static string $resourceClass = Resource::class;

    protected static string $modelClass = Model::class;


    protected static string $paginateQueryClass = UserCouponPaginateQuery::class;
    protected static string $findQueryClass     = UserCouponFindQuery::class;

    use RestQueryControllerActions;

    public function __construct(
        protected UserCouponApplicationService $service,
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyUser($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []) : bool
    {
        return true;
    }
}