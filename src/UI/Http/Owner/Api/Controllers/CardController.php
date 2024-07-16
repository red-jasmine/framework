<?php

namespace RedJasmine\Card\UI\Http\Owner\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Card\Application\Services\CardCommandService;
use RedJasmine\Card\Application\Services\CardQueryService;
use RedJasmine\Card\Application\UserCases\Command\CardCreateCommand;
use RedJasmine\Card\UI\Http\Owner\Api\Resources\CardResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class CardController extends Controller
{

    public function __construct(
        protected CardQueryService   $queryService,
        protected CardCommandService $commandService,

    )
    {
        $this->queryService->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->queryService->paginate(PaginateQuery::from($request));

        return CardResource::collection($result->appends($request->query()));
    }


    public function store(Request $request)
    {
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = CardCreateCommand::from($request);
        $result  = $this->commandService->create($command);

    }

}
