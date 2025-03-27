<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Series\Services\ProductSeriesApplicationService;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesCreateCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesDeleteCommand;
use RedJasmine\Product\Application\Series\UserCases\Commands\ProductSeriesUpdateCommand;
use RedJasmine\Product\Application\Series\UserCases\Queries\SeriesPaginateQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\SeriesResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class SeriesController extends Controller
{

    public function __construct(
        protected ProductSeriesApplicationService $service,

    ) {

    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $result = $this->service->paginate(SeriesPaginateQuery::from($request));

        return SeriesResource::collection($result->appends($request->all()));

    }

    public function show($id, Request $request) : SeriesResource
    {
        $result = $this->service->find(FindQuery::make($id, $request));

        return SeriesResource::make($result);
    }


    public function store(Request $request) : SeriesResource
    {

        $command = ProductSeriesCreateCommand::from($request);

        $result = $this->service->create($command);

        return SeriesResource::make($result);

    }


    public function update(Request $request, int $id) : JsonResponse
    {
        $request->offsetSet('id', $id);
        $command = ProductSeriesUpdateCommand::from($request);
        $this->service->update($command);
        return static::success();

    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = ProductSeriesDeleteCommand::from($request);

        $this->service->delete($command);

        return static::success();
    }
}
