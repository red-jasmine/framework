<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Brand\Services\BrandCommandService;
use RedJasmine\Product\Application\Brand\Services\BrandQueryService;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandCreateCommand;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandDeleteCommand;
use RedJasmine\Product\Application\Brand\UserCases\Commands\BrandUpdateCommand;
use RedJasmine\Product\Application\Brand\UserCases\Queries\BrandPaginateQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\BrandResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class BrandController extends Controller
{
    public function __construct(

        protected BrandQueryService   $queryService,
        protected BrandCommandService $commandService,
    )
    {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(BrandPaginateQuery::from($request));

        return BrandResource::collection($result->appends($request->query()));
    }

    public function show(Request $request, $id) : BrandResource
    {
        $result = $this->queryService->findById(FindQuery::make($id,$request));;

        return BrandResource::make($result);
    }

    public function store(Request $request) : BrandResource
    {
        $command = BrandCreateCommand::from($request);
        $result = $this->commandService->create($command);

        return BrandResource::make($result);
    }

    public function update($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = BrandUpdateCommand::from($request);
        $this->commandService->update($command);

        return static::success();

    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = BrandDeleteCommand::from($request);
        $this->commandService->delete($command);

        return static::success();
    }
}
