<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Coupon\Application\Services\Coupon\Queries\CouponPaginateQuery;
use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponReceiveCommand;
use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponUseCommand;
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
    protected static string $paginateQueryClass = CouponPaginateQuery::class;
    protected static string $modelClass = Model::class;
    protected static string $dataClass = Data::class;

    use RestControllerActions;

    public function __construct(
        protected CouponApplicationService $service,
        protected UserCouponApplicationService $userCouponService,
    ) {
        // 设置查询作用域 - 只显示已发布且可显示的优惠券
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
            $query->where('status', 'published');
            $query->where('is_show', true);
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        return true;
    }

    /**
     * 领取优惠券
     */
    public function receive(int $id, Request $request)
    {
        $request->offsetSet('couponId', $id);
        $request->offsetSet('owner', $this->getOwner());
        $request->offsetSet('user', $this->getOwner());
        
        $command = UserCouponReceiveCommand::from($request);
        $result = $this->userCouponService->receive($command);
        
        return static::success($result);
    }

    /**
     * 使用优惠券
     */
    public function consume(int $userCouponId, Request $request)
    {
        $request->offsetSet('userCouponId', $userCouponId);
        $request->offsetSet('owner', $this->getOwner());
        $request->offsetSet('user', $this->getOwner());
        
        $command = UserCouponUseCommand::from($request);
        $result = $this->userCouponService->use($command);
        
        return static::success($result);
    }
} 