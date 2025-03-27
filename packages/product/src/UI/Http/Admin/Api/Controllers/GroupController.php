<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupCreateCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupDeleteCommand;
use RedJasmine\Product\Application\Group\Services\Commands\ProductGroupUpdateCommand;
use RedJasmine\Product\Application\Group\Services\ProductGroupApplicationService;
use RedJasmine\Product\Application\Group\Services\Queries\ProductGroupPaginateQuery;
use RedJasmine\Product\Application\Group\Services\Queries\ProductGroupTreeQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\GroupResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class GroupController extends Controller
{
    public function __construct(

        protected ProductGroupApplicationService $service,
    ) {

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

    public function store(Request $request) : GroupResource
    {
        $command = ProductGroupCreateCommand::from($request);

        $result = $this->service->create($command);

        return GroupResource::make($result);
    }

    public function show($id, Request $request) : GroupResource
    {

        $result = $this->service->find(FindQuery::make($id, $request));

        return GroupResource::make($result);
    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductGroupUpdateCommand::from($request);
        $this->service->update($command);

        return static::success();
    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductGroupDeleteCommand::from($request);
        $this->service->delete($command);

        return static::success();
    }
}
