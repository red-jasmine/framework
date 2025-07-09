<?php

namespace RedJasmine\Coupon\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponUseCommand;
use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponPaginateQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Data\UserCouponData as Data;
use RedJasmine\Coupon\Domain\Models\UserCoupon as Model;
use RedJasmine\Coupon\UI\Http\User\Api\Resources\UserCouponResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class UserCouponController extends Controller
{
    protected static string $resourceClass = Resource::class;
    protected static string $paginateQueryClass = UserCouponPaginateQuery::class;
    protected static string $modelClass = Model::class;
    protected static string $dataClass = Data::class;

    use RestControllerActions;

    public function __construct(
        protected UserCouponApplicationService $service,
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        return true;
    }

    /**
     * 使用优惠券
     */
    public function consume(int $id, Request $request)
    {
        $request->offsetSet('userCouponId', $id);
        $request->offsetSet('owner', $this->getOwner());
        $request->offsetSet('user', $this->getOwner());
        
        $command = UserCouponUseCommand::from($request);
        $result = $this->service->use($command);
        
        return static::success($result);
    }
}