<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Category\Services\ProductCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductCategoryUpdateCommand;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryPaginateQuery;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\CategoryResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CategoryController extends Controller
{
    public function __construct(
        protected ProductCategoryQueryService $queryService,
        protected ProductCategoryCommandService $commandService,
    ) {

    }


    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->queryService->tree(ProductCategoryTreeQuery::from($request));

        return CategoryResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(ProductCategoryPaginateQuery::from($request));

        return CategoryResource::collection($result);
    }

    public function store(Request $request) : CategoryResource
    {
        $command = ProductCategoryCreateCommand::from($request);
        $result  = $this->commandService->create($command);

        return CategoryResource::make($result);
    }

    public function show(Request $request, $id) : CategoryResource
    {

        $result = $this->queryService->findById(FindQuery::make($id,$request));

        return CategoryResource::make($result);
    }

    public function update(Request $request, $id) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductCategoryUpdateCommand::from($request);
        $this->commandService->update($command);

        return static::success();
    }

    public function destroy($id, Request $request) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductCategoryDeleteCommand::from($request);
        $this->commandService->delete($command);

        return static::success();
    }
}
