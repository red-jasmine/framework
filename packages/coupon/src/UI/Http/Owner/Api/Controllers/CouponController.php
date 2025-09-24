<?php

namespace RedJasmine\Coupon\UI\Http\Owner\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponIssueCommand;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponPauseCommand;
use RedJasmine\Coupon\Application\Services\Coupon\Commands\CouponPublishCommand;
use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Application\Services\Coupon\Queries\CouponPaginateQuery as PaginateQuery;
use RedJasmine\Coupon\Domain\Data\CouponData as Data;
use RedJasmine\Coupon\Domain\Models\Coupon as Model;
use RedJasmine\Coupon\UI\Http\Owner\Api\Resources\CouponResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class CouponController extends Controller
{
    use RestControllerActions;

    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;


    public function __construct(
        protected CouponApplicationService $service,
    ) {
        $this->service->repository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }


    public function issue($id, Request $request) : JsonResponse
    {

        $this->findOne($id, $request);

        $command = CouponIssueCommand::from($request);
        $command->setKey($id);

        $this->service->issue($command);
        return static::success();
    }

    public function publish($id, Request $request) : JsonResponse
    {
        $this->findOne($id, $request);
        $command = CouponPublishCommand::from($request);
        $command->setKey($id);
        $this->service->publish($command);
        return static::success();
    }

    public function pause($id, Request $request) : JsonResponse
    {
        $this->findOne($id, $request);
        $command = CouponPauseCommand::from($request);
        $command->setKey($id);
        $this->service->pause($command);
        return static::success();
    }

}
