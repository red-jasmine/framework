<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupQueryService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupDeleteCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyGroupUpdateCommand;
use RedJasmine\Product\Application\Property\UserCases\Queries\PropertyGroupPaginateQuery;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\PropertyGroupResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class PropertyGroupController extends Controller
{
    public function __construct(
        protected ProductPropertyGroupCommandService $commandService,
        protected ProductPropertyGroupQueryService   $queryService,

    )
    {

    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->queryService->paginate(PropertyGroupPaginateQuery::from($request));
        return PropertyGroupResource::collection($result);
    }

    public function store(Request $request) : PropertyGroupResource
    {

        $result = $this->commandService->create(ProductPropertyGroupCreateCommand::from($request));
        return PropertyGroupResource::make($result);

    }

    public function show($id, Request $request) : PropertyGroupResource
    {
        $result = $this->queryService->find(FindQuery::fromRequestRoute($request,$id));;
        return PropertyGroupResource::make($result);

    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->commandService->update(ProductPropertyGroupUpdateCommand::from($request));
        return self::success();
    }

    public function destroy(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->commandService->delete(ProductPropertyGroupDeleteCommand::from($request));
        return self::success();
    }
}
