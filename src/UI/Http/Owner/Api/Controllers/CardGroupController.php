<?php

namespace RedJasmine\Card\UI\Http\Owner\Api\Controllers;


use Illuminate\Http\Request;
use RedJasmine\Card\Application\Services\CardGroupCommandService;
use RedJasmine\Card\Application\Services\CardGroupQueryService;
use RedJasmine\Card\Application\UserCases\Command\Groups\CardGroupCreateCommand;
use RedJasmine\Card\Application\UserCases\Command\Groups\CardGroupDeleteCommand;
use RedJasmine\Card\Application\UserCases\Command\Groups\CardGroupUpdateCommand;
use RedJasmine\Card\Application\UserCases\Queries\CardGroupPaginateQuery;
use RedJasmine\Card\UI\Http\Owner\Api\Resources\CardGroupResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CardGroupController extends Controller
{
    public function __construct(
        protected CardGroupCommandService $commandService,
        protected CardGroupQueryService   $queryService,

    )
    {
        $this->queryService->getRepository()->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }


    public function index(Request $request) : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(CardGroupPaginateQuery::from($request));

        return CardGroupResource::collection($result->appends($request->query()));

    }

    public function show($id, Request $request) : CardGroupResource
    {
        $result = $this->queryService->findById(FindQuery::make($id,$request));;

        return CardGroupResource::make($result);
    }


    public function store(Request $request) : CardGroupResource
    {
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());
        $command = CardGroupCreateCommand::from($request);


        $result = $this->commandService->create($command);

        return CardGroupResource::make($result);

    }


    public function update(Request $request, $id) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());
        $request->offsetSet('id', $id);

        $this->queryService->findById(FindQuery::make($id));


        $command = CardGroupUpdateCommand::from($request);

        $this->commandService->update($command);

        return static::success();

    }

    public function destroy(Request $request, $id) : \Illuminate\Http\JsonResponse
    {
        $this->queryService->findById(FindQuery::make($id));
        $request->offsetSet('id', $id);
        $command = CardGroupDeleteCommand::from($request);

        $this->commandService->delete($command);

        return static::success();
    }
}
