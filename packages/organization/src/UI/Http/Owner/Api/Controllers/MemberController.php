<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Controllers;

use RedJasmine\Organization\Application\Services\Member\MemberApplicationService;
use RedJasmine\Organization\Application\Services\Member\Queries\PaginateQuery;
use RedJasmine\Organization\Domain\Data\MemberData;
use RedJasmine\Organization\Domain\Models\Member;
use RedJasmine\Organization\UI\Http\Owner\Api\Resources\MemberResource;
use RedJasmine\Support\UI\Http\Controllers\Controller;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Controllers\UserOwnerTools;

class MemberController extends Controller
{
    use RestControllerActions;
    use UserOwnerTools;

    protected static string $resourceClass = MemberResource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass = Member::class;
    protected static string $dataClass = MemberData::class;

    public function __construct(
        protected MemberApplicationService $service,
    ) {
        // 设置查询作用域，只查询当前用户所属组织的成员
        $this->service->repository->withQuery(function ($query) {
            $query->where('org_id', $this->getOwner()->id);
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        // 权限验证逻辑
        return true;
    }
}
