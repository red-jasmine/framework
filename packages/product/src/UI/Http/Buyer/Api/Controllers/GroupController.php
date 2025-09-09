<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Controllers;

use RedJasmine\Product\Application\Group\Services\ProductGroupApplicationService as Service;
use RedJasmine\Product\Application\Group\Services\Queries\ProductGroupTreeQuery;
use RedJasmine\Product\Domain\Group\Models\ProductGroup as Model;
use RedJasmine\Product\UI\Http\Buyer\Api\Resources\GroupResource as Resource;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\UI\Http\Controllers\HasTreeAction;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class GroupController extends Controller
{

    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $treeQueryClass     = ProductGroupTreeQuery::class;
    protected static string $modelClass         = Model::class;


    use RestControllerActions;

    use HasTreeAction;

    public function __construct(
        protected Service $service,
    ) {
        $this->service->repository->withQuery(function ($query) {
            $query->show();
        });

    }

    public function authorize($ability, $arguments = []) : bool
    {
        return false;
    }


}
