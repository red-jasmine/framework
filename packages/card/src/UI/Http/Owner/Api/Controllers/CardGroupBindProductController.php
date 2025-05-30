<?php

namespace RedJasmine\Card\UI\Http\Owner\Api\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Card\Application\Services\CardGroupBindProductApplicationService;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductBindCommand;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductCreateCommand;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductDeleteCommand;
use RedJasmine\Card\Application\UserCases\Command\GroupBindProduct\CardGroupBindProductUpdateCommand;
use RedJasmine\Card\Application\UserCases\Queries\CardGroupBindProductPaginateQuery;
use RedJasmine\Card\UI\Http\Owner\Api\Resources\CardGroupBindProductResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;


class CardGroupBindProductController extends Controller
{
    public function __construct(
        protected CardGroupBindProductApplicationService $service,


    ) {
        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }


    /**
     * @param  Request  $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->service->paginate(CardGroupBindProductPaginateQuery::from($request));

        return CardGroupBindProductResource::collection($result->appends($request->query()));

    }

    public function show($id, Request $request) : CardGroupBindProductResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));;
        return CardGroupBindProductResource::make($result);
    }


    public function store(Request $request) : CardGroupBindProductResource
    {
        $request->offsetSet('owner_id', $this->getOwner()->getID());
        $request->offsetSet('owner_type', $this->getOwner()->getType());

        $command = CardGroupBindProductCreateCommand::from($request);
        $result  = $this->service->create($command);

        return CardGroupBindProductResource::make($result);

    }


    public function bind(Request $request) : CardGroupBindProductResource
    {
        $request->offsetSet('owner_id', $this->getOwner()->getID());
        $request->offsetSet('owner_type', $this->getOwner()->getType());

        $command = CardGroupBindProductBindCommand::from($request);
        $result  = $this->service->bind($command);

        return CardGroupBindProductResource::make($result);

    }


    public function update(Request $request, $id) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('id', $id);
        $request->offsetSet('owner_id', $this->getOwner()->getID());
        $request->offsetSet('owner_type', $this->getOwner()->getType());


        $this->service->find(FindQuery::make($id));

        $command = CardGroupBindProductUpdateCommand::from($request);
        $this->service->update($command);

        return static::success();

    }

    public function destroy($id, Request $request) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('id', $id);
        $request->offsetSet('owner_id', $this->getOwner()->getID());
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $this->service->find(FindQuery::make($id));
        $command = CardGroupBindProductDeleteCommand::from($request);

        $this->service->delete($command);

        return static::success();


    }
}
