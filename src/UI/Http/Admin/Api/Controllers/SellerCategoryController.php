<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryCommandService;
use RedJasmine\Product\Application\Category\Services\ProductSellerCategoryQueryService;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryCreateCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryDeleteCommand;
use RedJasmine\Product\Application\Category\UserCases\Commands\ProductSellerCategoryUpdateCommand;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductSellerCategoryPaginateQuery;
use RedJasmine\Product\Application\Category\UserCases\Queries\ProductSellerCategoryTreeQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\SellerCategoryResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;

class SellerCategoryController extends Controller
{
    public function __construct(
        protected ProductSellerCategoryQueryService   $queryService,
        protected ProductSellerCategoryCommandService $commandService,
    )
    {

    }


    public function tree(Request $request) : AnonymousResourceCollection
    {
        $tree = $this->queryService->tree(ProductSellerCategoryTreeQuery::from($request));

        return SellerCategoryResource::collection($tree);
    }

    public function index(Request $request) : AnonymousResourceCollection
    {


        $result = $this->queryService->paginate(ProductSellerCategoryPaginateQuery::from($request));

        return SellerCategoryResource::collection($result->appends($request->query()));
    }

    public function store(Request $request) : SellerCategoryResource
    {
        $command = ProductSellerCategoryCreateCommand::from($request);

        $result = $this->commandService->create($command);

        return SellerCategoryResource::make($result);
    }

    public function show($id, Request $request) : SellerCategoryResource
    {

        $result = $this->queryService->find($id, FindQuery::from($request));

        return SellerCategoryResource::make($result);
    }

    public function update(Request $request, $id) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductSellerCategoryUpdateCommand::from($request);
        $this->commandService->update($command);

        return static::success();
    }

    public function destroy($id, Request $request) : \Illuminate\Http\JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductSellerCategoryDeleteCommand::from($request);
        $this->commandService->delete($command);

        return static::success();
    }
}
