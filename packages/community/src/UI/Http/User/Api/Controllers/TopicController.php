<?php

namespace RedJasmine\Community\UI\Http\User\Api\Controllers;


use RedJasmine\Community\Application\Services\Topic\TopicApplicationService as Service;
use RedJasmine\Community\Application\Services\Topic\Queries\PaginateQuery;
use RedJasmine\Community\Domain\Data\TopicData as Data;
use RedJasmine\Community\Domain\Models\Topic as Model;
use RedJasmine\Community\UI\Http\User\Api\Resources\TopicResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class TopicController extends Controller
{


    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    use RestControllerActions;

    public function __construct(
        protected Service $service,
    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->show();
        });

    }

    public function authorize($ability, $arguments = []) : bool
    {
        return true;
    }
}
