<?php

namespace RedJasmine\Community\UI\Http\User\Api\Controllers;

use RedJasmine\Community\Application\Services\Category\TopicCategoryApplicationService as Service;
use RedJasmine\Community\Domain\Data\TopicCategoryData as Data;
use RedJasmine\Community\Domain\Models\TopicCategory as Model;
use RedJasmine\Community\UI\Http\User\Api\Resources\TopicCategoryResource as Resource;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\UI\Http\Controllers\HasTreeAction;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class TopicCategoryController extends Controller
{
    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

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
        return true;
    }


}
