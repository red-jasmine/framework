<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Series\Services\ProductSeriesCommandService;
use RedJasmine\Product\Application\Series\Services\ProductSeriesQueryService;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesCreateCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesDeleteCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesUpdateCommand;
use RedJasmine\Product\Application\Series\UserCases\Queries\SeriesPaginateQuery;
use RedJasmine\Product\UI\Http\Seller\Api\Resources\SeriesResource;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;

class SeriesController extends Controller
{

    public function __construct(
        protected ProductSeriesCommandService $commandService,
        protected ProductSeriesQueryService   $queryService,

    )
    {

        $this->queryService->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });

    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->queryService->paginate(SeriesPaginateQuery::from($request));

        return SeriesResource::collection($result->appends($request->all()));

    }

    public function show($id, Request $request) : SeriesResource
    {
        $result = $this->queryService->find($id, FindQuery::from($request));

        return SeriesResource::make($result);
    }


    public function store(Request $request) : SeriesResource
    {

        $request->offsetSet('owner_type', $this->getOwner()->getType());
        $request->offsetSet('owner_id', $this->getOwner()->getID());

        $command = ProductSeriesCreateCommand::from($request);

        $result = $this->commandService->create($command);

        return SeriesResource::make($result);

    }


    public function update(Request $request, int $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $command = ProductSeriesUpdateCommand::from($request);
        $this->commandService->update($command);
        return static::success();

    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductSeriesDeleteCommand::from($request);

        $this->commandService->delete($command);

        return static::success();
    }
}
