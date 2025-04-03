<?php

namespace RedJasmine\Interaction\UI\Http\User\Api\Controllers;

use RedJasmine\Interaction\Application\Services\InteractionRecordApplicationService;
use RedJasmine\Interaction\Application\Services\Queries\PaginateQuery;
use RedJasmine\Interaction\Domain\Data\InteractionData as Data;
use RedJasmine\Interaction\Domain\Models\InteractionRecord as Model;
use RedJasmine\Interaction\UI\Http\User\Api\Resources\InteractionRecordResource as Resource;

use RedJasmine\Support\UI\Http\Controllers\RestControllerActions;

class InteractionRecordController extends Controller
{

    protected static string $resourceClass      = Resource::class;
    protected static string $paginateQueryClass = PaginateQuery::class;
    protected static string $modelClass         = Model::class;
    protected static string $dataClass          = Data::class;

    use RestControllerActions;



    public function authorize($ability, $arguments = [])
    {
        return true;
    }
    public function __construct(
        protected InteractionRecordApplicationService $service,
    ) {
    }


}