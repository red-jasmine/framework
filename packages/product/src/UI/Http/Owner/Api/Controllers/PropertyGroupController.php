<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyGroupCreateCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyGroupDeleteCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyGroupUpdateCommand;
use RedJasmine\Product\Application\Property\Services\ProductPropertyGroupApplicationService;
use RedJasmine\Product\Application\Property\Services\Queries\PropertyGroupPaginateQuery;
use RedJasmine\Product\UI\Http\Owner\Api\Resources\PropertyGroupResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class PropertyGroupController extends Controller
{
    public function __construct(
        protected ProductPropertyGroupApplicationService $service,

    ) {

    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->service->paginate(PropertyGroupPaginateQuery::from($request));
        return PropertyGroupResource::collection($result);
    }

    public function store(Request $request) : PropertyGroupResource
    {

        $result = $this->service->create(ProductPropertyGroupCreateCommand::from($request));
        return PropertyGroupResource::make($result);

    }

    public function show($id, Request $request) : PropertyGroupResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));
        return PropertyGroupResource::make($result);

    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->service->update(ProductPropertyGroupUpdateCommand::from($request));
        return self::success();
    }

    public function destroy(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->service->delete(ProductPropertyGroupDeleteCommand::from($request));
        return self::success();
    }
}
