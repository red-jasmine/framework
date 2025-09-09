<?php

namespace RedJasmine\PointsMall\UI\Http\User\Api\Controllers;

use RedJasmine\PointsMall\Application\Services\PointsProductCategory\PointsProductCategoryApplicationService as Service;
use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\PointsMall\UI\Http\User\Api\Resources\PointProductCategoryResource;
use RedJasmine\Support\UI\Http\Controllers\HasTreeAction;
use RedJasmine\Support\UI\Http\Controllers\RestQueryControllerActions;

class PointProductCategoryController extends Controller
{
    use RestQueryControllerActions, HasTreeAction;

    protected static string $resourceClass = PointProductCategoryResource::class;
    protected static string $modelClass    = PointsProductCategory::class;

    public function __construct(
        protected Service $service,
    ) {
        // 设置查询作用域，只显示启用的分类
        $this->service->repository->withQuery(function ($query) {
            $query->where('is_show', true);
        });
    }

    public function authorize($ability, $arguments = []) : bool
    {
        // 用户端权限验证逻辑
        return true;
    }
}