<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Product\Services\Commands\ProductCreateCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductDeleteCommand;
use RedJasmine\Product\Application\Product\Services\Commands\ProductUpdateCommand;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\ProductResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductController extends Controller
{

    public function __construct(
        protected ProductApplicationService $service,


    ) {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->service->paginate(PaginateQuery::from($request->all()));

        return ProductResource::collection($result->appends($request->query()));
    }

    public function show($id, Request $request) : ProductResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));
        return ProductResource::make($result);
    }


    public function store(Request $request) : ProductResource
    {
        $command = ProductCreateCommand::from($request);

        $result = $this->service->create($command);
        return ProductResource::make($result);
    }


    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $command = ProductUpdateCommand::from($request);


        $this->service->update($command);

        return static::success();

    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductDeleteCommand::from($request);

        $this->service->delete($command);

        return static::success();
    }
}
