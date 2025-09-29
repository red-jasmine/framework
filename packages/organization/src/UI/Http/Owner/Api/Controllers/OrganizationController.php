<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Controllers;

use RedJasmine\Organization\Application\Services\Organization\OrganizationApplicationService;
use RedJasmine\Organization\Application\Services\Organization\Queries\PaginateQuery;
use RedJasmine\Organization\Domain\Data\OrganizationData;
use RedJasmine\Organization\Domain\Models\Organization;
use RedJasmine\Support\Http\Controllers\Controller;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Controllers\UserOwnerTools;

class OrganizationController extends Controller
{
    use RestControllerActions;
    use UserOwnerTools;

    protected static string $resourceClass = \RedJasmine\Organization\UI\Http\Owner\Api\Resources\OrganizationResource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass = Organization::class;
    protected static string $dataClass = OrganizationData::class;

    public function __construct(
        protected OrganizationApplicationService $service,
    ) {
        // 设置查询作用域，只查询当前用户所属的组织
        $this->service->repository->withQuery(function ($query) {
            $query->where('owner_id', $this->getOwner()->id);
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        return true;
    }
}
