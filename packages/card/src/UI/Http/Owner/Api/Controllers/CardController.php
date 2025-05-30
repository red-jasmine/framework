<?php

namespace RedJasmine\Card\UI\Http\Owner\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Card\Application\Services\CardApplicationService;
use RedJasmine\Card\Application\UserCases\Command\CardCreateCommand;
use RedJasmine\Card\Application\UserCases\Command\CardDeleteCommand;
use RedJasmine\Card\Application\UserCases\Command\CardUpdateCommand;
use RedJasmine\Card\Application\UserCases\Queries\CardPaginateQuery;
use RedJasmine\Card\UI\Http\Owner\Api\Resources\CardResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CardController extends Controller
{

    public function __construct(
        protected CardApplicationService $service,

    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });


    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->service->paginate(CardPaginateQuery::from($request));

        return CardResource::collection($result->appends($request->query()));
    }

    public function show($id, Request $request) : CardResource
    {

        $result = $this->service->find(FindQuery::make($id, $request));;

        return CardResource::make($result);

    }


    public function store(Request $request) : CardResource
    {
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = CardCreateCommand::from($request);

        $result = $this->service->create($command);

        return CardResource::make($result);

    }

    public function update(Request $request, $id) : \Illuminate\Http\JsonResponse
    {

        $this->service->find(FindQuery::make($id));
        $request->offsetSet('id', $id);
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = CardUpdateCommand::from($request);

        $this->service->update($command);
        return static::success();
    }


    public function destroy($id, Request $request) : \Illuminate\Http\JsonResponse
    {
        $this->service->find(FindQuery::make($id));
        $request->offsetSet('id', $id);
        $command = CardDeleteCommand::from($request);

        $this->service->delete($command);

        return static::success();
    }

}
