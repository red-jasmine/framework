<?php

namespace RedJasmine\Card\UI\Http\Owner\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Card\Application\Services\CardCommandService;
use RedJasmine\Card\Application\Services\CardQueryService;
use RedJasmine\Card\Application\UserCases\Command\CardCreateCommand;
use RedJasmine\Card\Application\UserCases\Command\CardDeleteCommand;
use RedJasmine\Card\Application\UserCases\Command\CardUpdateCommand;
use RedJasmine\Card\Application\UserCases\Queries\CardPaginateQuery;
use RedJasmine\Card\UI\Http\Owner\Api\Resources\CardResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CardController extends Controller
{

    public function __construct(
        protected CardQueryService   $queryService,
        protected CardCommandService $commandService,

    )
    {
        $this->queryService->getRepository()->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });


    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->queryService->paginate(CardPaginateQuery::from($request));

        return CardResource::collection($result->appends($request->query()));
    }

    public function show($id, Request $request) : CardResource
    {

        $result = $this->queryService->findById(FindQuery::make($id,$request));;

        return CardResource::make($result);

    }


    public function store(Request $request) : CardResource
    {
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = CardCreateCommand::from($request);

        $result = $this->commandService->create($command);

        return CardResource::make($result);

    }

    public function update(Request $request, $id) : \Illuminate\Http\JsonResponse
    {

        $this->queryService->findById(FindQuery::make($id));
        $request->offsetSet('id', $id);
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = CardUpdateCommand::from($request);

        $this->commandService->update($command);
        return static::success();
    }


    public function destroy($id, Request $request) : \Illuminate\Http\JsonResponse
    {
        $this->queryService->findById(FindQuery::make($id));
        $request->offsetSet('id', $id);
        $command = CardDeleteCommand::from($request);

        $this->commandService->delete($command);

        return static::success();
    }

}
