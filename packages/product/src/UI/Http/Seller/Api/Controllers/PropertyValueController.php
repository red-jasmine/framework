<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyValueCreateCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyValueDeleteCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyValueUpdateCommand;
use RedJasmine\Product\Application\Property\Services\ProductPropertyValueApplicationService;
use RedJasmine\Product\Application\Property\Services\Queries\PropertyValuePaginateQuery;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\PropertyValueResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class PropertyValueController extends Controller
{
    public function __construct(
        protected ProductPropertyValueApplicationService $service,

    ) {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->service->paginate(PropertyValuePaginateQuery::from($request));

        return PropertyValueResource::collection($result);

    }

    public function store(Request $request) : PropertyValueResource
    {

        $result = $this->service->create(ProductPropertyValueCreateCommand::from($request));

        return PropertyValueResource::make($result);
    }

    public function show($id, Request $request) : PropertyValueResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));

        return PropertyValueResource::make($result);
    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->service->update(ProductPropertyValueUpdateCommand::from($request));
        return static::success();
    }

    public function destroy(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $this->service->delete(ProductPropertyValueDeleteCommand::from($request));

        return static::success();
    }
}
