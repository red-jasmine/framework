<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Attribute\Services\ProductAttributeValueApplicationService;
use RedJasmine\Product\Application\Attribute\Services\Queries\ProductAttributeValuePaginateQuery;
use RedJasmine\Product\Domain\Attribute\Data\ProductAttributeValueData;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\AttributeValueResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Foundation\Data\Data;

class AttributeValueController extends Controller
{
    public function __construct(
        protected ProductAttributeValueApplicationService $service,


    ) {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->service->paginate(ProductAttributeValuePaginateQuery::from($request));

        return AttributeValueResource::collection($result);

    }

    public function store(Request $request) : AttributeValueResource
    {

        $result = $this->service->create(ProductAttributeValueData::from($request));

        return AttributeValueResource::make($result);
    }

    public function show($id, Request $request) : AttributeValueResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));

        return AttributeValueResource::make($result);
    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $this->service->update(ProductAttributeValueData::from($request));
        return static::success();
    }

    public function destroy(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $this->service->delete(Data::from($request));

        return static::success();
    }
}
