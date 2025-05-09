<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Category\Services\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\Services\Commands\ProductCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\Services\Commands\ProductCategoryUpdateCommand;
use RedJasmine\Product\Application\Category\Services\ProductCategoryApplicationService;
use RedJasmine\Product\Application\Category\Services\Queries\ProductCategoryPaginateQuery;
use RedJasmine\Product\Application\Category\Services\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\CategoryResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CategoryController extends Controller
{
    public function __construct(
        protected ProductCategoryApplicationService $service,
    ) {

    }


    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->service->tree(ProductCategoryTreeQuery::from($request));

        return CategoryResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->service->paginate(ProductCategoryPaginateQuery::from($request));

        return CategoryResource::collection($result);
    }

    public function store(Request $request) : CategoryResource
    {
        $command = ProductCategoryCreateCommand::from($request);
        $result  = $this->service->create($command);

        return CategoryResource::make($result);
    }

    public function show(Request $request, $id) : CategoryResource
    {

        $result = $this->service->find(FindQuery::make($id, $request));

        return CategoryResource::make($result);
    }

    public function update(Request $request, $id) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductCategoryUpdateCommand::from($request);
        $this->service->update($command);

        return static::success();
    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductCategoryDeleteCommand::from($request);
        $this->service->delete($command);

        return static::success();
    }
}
