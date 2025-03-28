<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyCreateCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyDeleteCommand;
use RedJasmine\Product\Application\Property\Services\Commands\ProductPropertyUpdateCommand;
use RedJasmine\Product\Application\Property\Services\ProductPropertyApplicationService;
use RedJasmine\Product\Application\Property\Services\Queries\PropertyPaginateQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\PropertyResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class PropertyController extends Controller
{
    public function __construct(
        protected ProductPropertyApplicationService $service,


    ) {
    }

    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->service->paginate(PropertyPaginateQuery::from($request));
        return PropertyResource::collection($result);
    }

    public function store(Request $request) : PropertyResource
    {
        $result = $this->service->create(ProductPropertyCreateCommand::from($request));
        return PropertyResource::make($result);
    }

    public function show($id, Request $request) : PropertyResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));

        return PropertyResource::make($result);
    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->service->update(ProductPropertyUpdateCommand::from($request));
        return static::success();
    }

    public function destroy(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->service->delete(ProductPropertyDeleteCommand::from($request));
        return self::success();
    }
}
