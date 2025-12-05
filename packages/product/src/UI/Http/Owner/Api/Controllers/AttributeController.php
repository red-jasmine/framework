<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeApplicationService;
use RedJasmine\Product\Application\Attribute\Services\Queries\ProductAttributePaginateQuery;
use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeData;
use RedJasmine\Product\UI\Http\Owner\Api\Resources\AttributeResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Foundation\Data\Data;

class AttributeController extends Controller
{
    public function __construct(
        protected ProductAttributeApplicationService $service,

    ) {
    }

    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->service->paginate(ProductAttributePaginateQuery::from($request));
        return AttributeResource::collection($result);
    }

    public function store(Request $request) : AttributeResource
    {
        $result = $this->service->create(ProductAttributeData::from($request));
        return AttributeResource::make($result);
    }

    public function show($id, Request $request) : AttributeResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));

        return AttributeResource::make($result);
    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->service->update(ProductAttributeData::from($request));
        return static::success();
    }

    public function destroy(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->service->delete(Data::from($request));
        return self::success();
    }
}

