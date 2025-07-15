<?php

namespace RedJasmine\Coupon\UI\Http\Shop\Api\Controllers;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedJasmine\Coupon\Application\Services\Coupon\CouponApplicationService;
use RedJasmine\Coupon\Application\Services\Coupon\Queries\CouponPaginateQuery as PaginateQuery;
use RedJasmine\Coupon\Domain\Data\CouponData as Data;
use RedJasmine\Coupon\Domain\Models\Coupon as Model;
use RedJasmine\Coupon\UI\Http\Shop\Api\Resources\CouponResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

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
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }
}