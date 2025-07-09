<?php

namespace RedJasmine\Coupon\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponIssueCommand;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponPauseCommand;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponPublishCommand;
use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Application\Services\Coupon\Queries\CouponPaginateQuery;
use RedJasmine\Coupon\Domain\Data\CouponData as Data;
use RedJasmine\Coupon\Domain\Models\Coupon as Model;
use RedJasmine\Coupon\UI\Http\Admin\Api\Resources\CouponResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class CouponController extends Controller
{
    protected static string $resourceClass = Resource::class;
    protected static string $paginateQueryClass = CouponPaginateQuery::class;
    protected static string $modelClass = Model::class;
    protected static string $dataClass = Data::class;

    use RestControllerActions;

    public function __construct(
        protected CouponApplicationService $service,
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
     * 发布优惠券
     */
    public function publish(int $id, Request $request)
    {
        $command = new CouponPublishCommand();
        $command->setKey($id);
        $command->owner = $this->getOwner();
        $command->operator = $this->getOwner();
        
        $result = $this->service->publish($command);
        
        return static::success($result);
    }

    /**
     * 暂停优惠券
     */
    public function pause(int $id, Request $request)
    {
        $command = new CouponPauseCommand();
        $command->setKey($id);
        $command->owner = $this->getOwner();
        $command->operator = $this->getOwner();
        
        $result = $this->service->pause($command);
        
        return static::success($result);
    }

    /**
     * 发放优惠券
     */
    public function issue(int $id, Request $request)
    {
        $command = new CouponIssueCommand();
        $command->setKey($id);
        $command->owner = $this->getOwner();
        $command->operator = $this->getOwner();
        
        $result = $this->service->issue($command);
        
        return static::success($result);
    }
}