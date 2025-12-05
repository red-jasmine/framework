<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Controllers;

use RedJasmine\Organization\Application\Services\MemberDepartment\MemberDepartmentApplicationService;
use RedJasmine\Organization\Application\Services\MemberDepartment\Queries\PaginateQuery;
use RedJasmine\Organization\Domain\Data\MemberDepartmentData;
use RedJasmine\Organization\Domain\Models\MemberDepartment;
use RedJasmine\Support\UI\Http\Controllers\Controller;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Controllers\UserOwnerTools;

class MemberDepartmentController extends Controller
{
    use RestControllerActions;
    use UserOwnerTools;

    protected static string $resourceClass = \RedJasmine\Organization\UI\Http\Owner\Api\Resources\MemberDepartmentResource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass = MemberDepartment::class;
    protected static string $dataClass = MemberDepartmentData::class;

    public function __construct(
        protected MemberDepartmentApplicationService $service,
    ) {
        // 设置查询作用域，只查询当前用户所属组织的成员部门关系
        $this->service->repository->withQuery(function ($query) {
            $query->whereHas('member', function ($q) {
                $q->where('org_id', $this->getOwner()->id);
            });
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        return true;
    }
}
