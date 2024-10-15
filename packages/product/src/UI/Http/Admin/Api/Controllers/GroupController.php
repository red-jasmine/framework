<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Group\Services\ProductGroupCommandService;
use RedJasmine\Product\Application\Group\Services\ProductGroupQueryService;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupDeleteCommand;
use RedJasmine\Product\Application\Group\UserCases\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Application\Group\UserCases\Queries\ProductGroupPaginateQuery;
use RedJasmine\Product\Application\Group\UserCases\Queries\ProductGroupTreeQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\GroupResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class GroupController extends Controller
{
    public function __construct(
        protected ProductGroupQueryService   $queryService,
        protected ProductGroupCommandService $commandService,
    )
    {

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

    public function store(Request $request) : GroupResource
    {
        $command = ProductGroupCreateCommand::from($request);

        $result = $this->commandService->create($command);

        return GroupResource::make($result);
    }

    public function show($id, Request $request) : GroupResource
    {

        $result = $this->queryService->findById(FindQuery::make($id,$request));;

        return GroupResource::make($result);
    }

    public function update(Request $request, $id) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductGroupUpdateCommand::from($request);
        $this->commandService->update($command);

        return static::success();
    }

    public function destroy($id, Request $request) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductGroupDeleteCommand::from($request);
        $this->commandService->delete($command);

        return static::success();
    }
}
