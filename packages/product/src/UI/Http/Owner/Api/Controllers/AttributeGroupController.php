<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeGroupApplicationService;
use RedJasmine\Product\Application\Attribute\Services\Queries\ProductAttributeGroupPaginateQuery;
use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeGroupData;
use RedJasmine\Product\UI\Http\Owner\Api\Resources\AttributeGroupResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Foundation\Data\Data;

class AttributeGroupController extends Controller
{
    public function __construct(
        protected ProductAttributeGroupApplicationService $service,

    ) {

    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $result = $this->service->paginate(ProductAttributeGroupPaginateQuery::from($request));
        return AttributeGroupResource::collection($result);
    }

    public function store(Request $request) : AttributeGroupResource
    {

        $result = $this->service->create(ProductAttributeGroupData::from($request));
        return AttributeGroupResource::make($result);

    }

    public function show($id, Request $request) : AttributeGroupResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));
        return AttributeGroupResource::make($result);

    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->service->update(ProductAttributeGroupData::from($request));
        return self::success();
    }

    public function destroy(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->service->delete(Data::from($request));
        return self::success();
    }
}

