<?php

namespace RedJasmine\Article\UI\Http\User\Api\Controllers;


use RedJasmine\Article\Application\Services\Article\ArticleApplicationService;
use RedJasmine\Article\Application\Services\Article\Queries\PaginateQuery;
use RedJasmine\Article\Domain\Data\ArticleData as Data;
use RedJasmine\Article\Domain\Models\Article as Model;
use RedJasmine\Article\UI\Http\User\Api\Resources\ArticleResource as Resource;
use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class ArticleController extends Controller
{


    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    use RestControllerActions;

    public function __construct(
        protected ArticleApplicationService $service,
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
