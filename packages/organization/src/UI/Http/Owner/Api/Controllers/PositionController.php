<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Controllers;

use RedJasmine\Organization\Application\Services\Position\PositionApplicationService;
use RedJasmine\Organization\Application\Services\Position\Queries\PaginateQuery;
use RedJasmine\Organization\Domain\Data\PositionData;
use RedJasmine\Organization\Domain\Models\Position;
use RedJasmine\Organization\UI\Http\Owner\Api\Resources\PositionResource;
use RedJasmine\Support\UI\Http\Controllers\Controller;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;
use RedJasmine\Support\UI\Http\Controllers\UserOwnerTools;

class PositionController extends Controller
{
    use RestControllerActions;
    use UserOwnerTools;

    protected static string $resourceClass = PositionResource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass = Position::class;
    protected static string $dataClass = PositionData::class;

    public function __construct(
        protected PositionApplicationService $service,
    ) {
        // 设置查询作用域，只查询当前用户所属组织的职位
        $this->service->repository->withQuery(function ($query) {
            $query->where('org_id', $this->getOwner()->id);
        });
    }

    public function authorize($ability, $arguments = []): bool
    {
        return true;
    }
}
