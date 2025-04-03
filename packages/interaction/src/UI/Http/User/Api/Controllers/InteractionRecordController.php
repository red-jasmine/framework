<?php

namespace RedJasmine\Interaction\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use RedJasmine\Interaction\Application\Services\Commands\InteractionCancelCommand;
use RedJasmine\Interaction\Application\Services\InteractionRecordApplicationService;
use RedJasmine\Interaction\Application\Services\Queries\FindQuery;
use RedJasmine\Interaction\Application\Services\Queries\PaginateQuery;
use RedJasmine\Interaction\Application\Services\Queries\ResourceUserFindQuery;
use RedJasmine\Interaction\Application\Services\Queries\StatisticQuery;
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
    protected string        $findQueryClass     = FindQuery::class;


    use RestControllerActions;

    protected string $ownerKey = 'user';


    public function authorize($ability, $arguments = [])
    {
        return true;
    }

    public function __construct(
        protected InteractionRecordApplicationService $service,
    ) {
    }


    /**
     * @param  Request  $request
     *
     * @return JsonResponse|JsonResource
     */
    public function statistic(Request $request) : JsonResponse|JsonResource
    {
        $query = StatisticQuery::from($request);

        $result = $this->service->statistic($query);

        return static::success($result);

    }

    /**
     * @param  Request  $request
     *
     * @return JsonResponse|JsonResource
     */
    public function cancel(Request $request) : JsonResponse|JsonResource
    {
        // 查询互动记录

        $request->offsetSet($this->getOwnerKey(), $this->getOwner());

        $query = ResourceUserFindQuery::from($request);

        $lastInteraction = $this->service->readRepository->findByResourceUserLast($query);


        $command = InteractionCancelCommand::from($request);
        $command->setKey($lastInteraction->id);


        $result = $this->service->cancel($command);
        return static::success($result);
    }


}