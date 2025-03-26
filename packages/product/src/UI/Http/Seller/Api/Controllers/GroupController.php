<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Group\Services\ProductGroupCommandService;
use RedJasmine\Product\Application\Group\Services\ProductGroupQueryService;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupDeleteCommand;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Application\Group\UserCases\Queries\ProductGroupPaginateQuery;
use RedJasmine\Product\Application\Group\UserCases\Queries\ProductGroupTreeQuery;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\GroupResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class GroupController extends Controller
{
    public function __construct(
        protected ProductGroupQueryService   $queryService,
        protected ProductGroupCommandService $commandService,
    )
    {

        $this->queryService->getRepository()->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }


    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->queryService->tree(ProductGroupTreeQuery::from($request));

        return GroupResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(ProductGroupPaginateQuery::from($request));

        return GroupResource::collection($result->appends($request->query()));
    }

    public function show($id, Request $request) : GroupResource
    {

        $result = $this->queryService->find(FindQuery::make($id,$request));;

        return GroupResource::make($result);
    }


    public function store(Request $request) : GroupResource
    {
        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = ProductGroupCreateCommand::from($request);

        $result = $this->commandService->create($command);

        return GroupResource::make($result);
    }


    public function update(Request $request, $id) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->queryService->find(FindQuery::make($id,$request));;

        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());
        $command = ProductGroupUpdateCommand::from($request);
        $this->commandService->update($command);

        return static::success();
    }

    public function destroy($id, Request $request) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('owner', $this->getOwner());
        $request->offsetSet('id', $id);
        $this->queryService->find(FindQuery::make($id,$request));;
        $command = ProductGroupDeleteCommand::from($request);
        $this->commandService->delete($command);

        return static::success();
    }
}
