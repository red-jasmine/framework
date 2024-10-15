<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueQueryService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueDeleteCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyValueUpdateCommand;
use RedJasmine\Product\Application\Property\UserCases\Queries\PropertyValuePaginateQuery;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\PropertyValueResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class PropertyValueController extends Controller
{
    public function __construct(
        protected ProductPropertyValueCommandService $commandService,
        protected ProductPropertyValueQueryService   $queryService,

    )
    {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(PropertyValuePaginateQuery::from($request));

        return PropertyValueResource::collection($result);

    }

    public function store(Request $request) : PropertyValueResource
    {

        $result = $this->commandService->create(ProductPropertyValueCreateCommand::from($request));

        return PropertyValueResource::make($result);
    }

    public function show($id, Request $request) : PropertyValueResource
    {
        $result = $this->queryService->findById(FindQuery::make($id,$request));;

        return PropertyValueResource::make($result);
    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->commandService->update(ProductPropertyValueUpdateCommand::from($request));
        return static::success();
    }

    public function destroy(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $this->commandService->delete(ProductPropertyValueDeleteCommand::from($request));

        return static::success();
    }
}
