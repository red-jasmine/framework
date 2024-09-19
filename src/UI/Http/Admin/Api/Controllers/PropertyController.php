<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Property\Services\ProductPropertyCommandService;
use RedJasmine\Product\Application\Property\Services\ProductPropertyQueryService;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyDeleteCommand;
use RedJasmine\Product\Application\Property\UserCases\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Application\Property\UserCases\Queries\PropertyPaginateQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\PropertyResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class PropertyController extends Controller
{
    public function __construct(
        protected ProductPropertyCommandService $commandService,
        protected ProductPropertyQueryService   $queryService,

    )
    {
    }

    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->queryService->paginate(PropertyPaginateQuery::from($request));
        return PropertyResource::collection($result);
    }

    public function store(Request $request) : PropertyResource
    {
        $result = $this->commandService->create(ProductPropertyCreateCommand::from($request));
        return PropertyResource::make($result);
    }

    public function show($id, Request $request) : PropertyResource
    {
        $result = $this->queryService->find(FindQuery::fromRequestRoute($request,$id));;

        return PropertyResource::make($result);
    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->commandService->update(ProductPropertyUpdateCommand::from($request));
        return static::success();
    }

    public function destroy(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->commandService->delete(ProductPropertyDeleteCommand::from($request));
        return self::success();
    }
}
