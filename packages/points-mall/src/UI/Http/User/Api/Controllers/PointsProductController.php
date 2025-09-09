<?php

namespace RedJasmine\PointsMall\UI\Http\User\Api\Controllers;

use RedJasmine\PointsMall\Application\Services\PointsProduct\PointsProductApplicationService;
use RedJasmine\PointsMall\Application\Services\PointsProduct\Queries\PointsProductFindQuery;
use RedJasmine\PointsMall\Application\Services\PointsProduct\Queries\PointsProductPaginationQuery;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\PointsMall\UI\Http\User\Api\Resources\PointsProductResource;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

class PointsProductController extends Controller
{
    use RestQueryControllerActions;

    protected static string $resourceClass      = PointsProductResource::class;
    protected static string $paginateQueryClass = PointsProductPaginationQuery::class;
    protected static string $modelClass         = PointsProduct::class;
    protected static string $findQueryClass     = PointsProductFindQuery::class;

    public function __construct(
        protected PointsProductApplicationService $service,
    ) {
        // 设置查询作用域，只显示上架的商品
        $this->service->repository->withQuery(function ($query) {
            //$query->where('status', 'on_sale');
        });
    }

    public function authorize($ability, $arguments = []) : bool
    {
        // 用户端权限验证逻辑
        return true;
    }




}