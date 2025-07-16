<?php

declare(strict_types = 1);

namespace RedJasmine\Coupon\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Application\Services\Coupon\Queries\UserCouponPaginateQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponReceiveCommand;
use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Data\CouponData as Data;
use RedJasmine\Coupon\Domain\Models\Coupon as Model;
use RedJasmine\Coupon\UI\Http\User\Api\Resources\CouponResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

/**
 * 优惠券用户端控制器
 */
class CouponController extends Controller
{
    protected static string $resourceClass = Resource::class;
    protected static string $modelClass    = Model::class;
    protected static string $dataClass     = Data::class;

    protected static string $paginateQueryClass = UserCouponPaginateQuery::class;

    use RestControllerActions;

    public function __construct(
        protected CouponApplicationService $service,
        protected UserCouponApplicationService $userCouponService,
    ) {

    }

    public function authorize($ability, $arguments = []) : bool
    {
        return true;
    }

    /**
     * 领取优惠券
     */
    public function receive(int $id, Request $request) : JsonResponse
    {
        $request->offsetSet('coupon_id', $id);

        $request->offsetSet('buyer', $this->getOwner()->getUserData());

        $command = UserCouponReceiveCommand::from($request);
        $command->setKey($id);

        $result = $this->userCouponService->receive($command);

        return static::success();
    }


} 