<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Controllers;

use RedJasmine\Organization\Application\Services\DepartmentManager\DepartmentManagerApplicationService;
use RedJasmine\Organization\Application\Services\DepartmentManager\Queries\PaginateQuery;
use RedJasmine\Organization\Domain\Data\DepartmentManagerData;
use RedJasmine\Organization\Domain\Models\DepartmentManager;
use RedJasmine\Support\Http\Controllers\Controller;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Controllers\UserOwnerTools;

class DepartmentManagerController extends Controller
{
    use RestControllerActions;
    use UserOwnerTools;

    protected static string $resourceClass = \RedJasmine\Organization\UI\Http\Owner\Api\Resources\DepartmentManagerResource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass = DepartmentManager::class;
    protected static string $dataClass = DepartmentManagerData::class;

    public function __construct(
        protected DepartmentManagerApplicationService $service,
    ) {
        // 设置查询作用域，只查询当前用户所属组织的部门管理员
        $this->service->repository->withQuery(function ($query) {
            $query->whereHas('department', function ($q) {
                $q->where('org_id', $this->getOwner()->id);
            });
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        return true;
    }
}
