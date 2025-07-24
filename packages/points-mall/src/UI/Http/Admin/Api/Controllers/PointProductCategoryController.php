<?php

namespace RedJasmine\PointsMall\UI\Http\Admin\Api\Controllers;

use RedJasmine\PointsMall\Application\Services\PointsProductCategory\PointsProductCategoryApplicationService as Service;
use RedJasmine\PointsMall\Domain\Data\PointProductCategoryData as Data;
use RedJasmine\PointsMall\Domain\Models\PointsProductCategory as Model;
use RedJasmine\PointsMall\UI\Http\Admin\Api\Resources\PointProductCategoryResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\HasTreeAction;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class PointProductCategoryController extends Controller
{
    protected static string $resourceClass      = Resource::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    use RestControllerActions;

    public function __construct(
        protected Service $service,
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    public function authorize($ability, $arguments = [])
    {
        return true;
    }


    use HasTreeAction;
} 