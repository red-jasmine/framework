<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupDeleteCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Application\Group\Services\ProductGroupApplicationService;
use RedJasmine\Product\Application\Group\Services\Queries\ProductGroupPaginateQuery;
use RedJasmine\Product\Application\Group\Services\Queries\ProductGroupTreeQuery;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\GroupResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class GroupController extends Controller
{
    public function __construct(

        protected ProductGroupApplicationService $service,
    ) {

        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }


    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->service->tree(ProductGroupTreeQuery::from($request));

        return GroupResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->service->paginate(ProductGroupPaginateQuery::from($request));

        return GroupResource::collection($result->appends($request->query()));
    }

    public function show($id, Request $request) : GroupResource
    {

        $result = $this->service->find(FindQuery::make($id, $request));

        return GroupResource::make($result);
    }


    public function store(Request $request) : GroupResource
    {
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = ProductGroupCreateCommand::from($request);

        $result = $this->service->create($command);

        return GroupResource::make($result);
    }


    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->queryService->find(FindQuery::make($id, $request));

        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());
        $command = ProductGroupUpdateCommand::from($request);
        $this->service->update($command);

        return static::success();
    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $request->offsetSet('owner', $this->getOwner());
        $request->offsetSet('id', $id);
        $this->service->find(FindQuery::make($id, $request));
        $command = ProductGroupDeleteCommand::from($request);
        $this->service->delete($command);

        return static::success();
    }
}
