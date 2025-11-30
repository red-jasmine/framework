<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Brand\Services\BrandApplicationService;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandCreateCommand;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandDeleteCommand;
use RedJasmine\Product\Application\Brand\Services\Commands\BrandUpdateCommand;
use RedJasmine\Product\Application\Brand\Services\Queries\BrandPaginateQuery;
use RedJasmine\Product\UI\Http\Admin\Api\Resources\BrandResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class BrandController extends Controller
{
    public function __construct(
        protected BrandApplicationService $service,
    ) {
    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $locale = $request->get('locale', app()->getLocale());
        
        // 预加载翻译数据
        $this->service->repository->withQuery(function ($query) use ($locale) {
            $query->withTranslation($locale);
        });

        $result = $this->service->paginate(BrandPaginateQuery::from($request));

        return BrandResource::collection($result->appends($request->query()));
    }

    public function show(Request $request, $id) : BrandResource
    {
        $locale = $request->get('locale', app()->getLocale());
        
        // 预加载翻译数据
        $this->service->repository->withQuery(function ($query) use ($locale) {
            $query->withTranslation($locale);
        });

        $result = $this->service->find(FindQuery::make($id, $request));

        return BrandResource::make($result);
    }

    public function store(Request $request) : BrandResource
    {
        $command = BrandCreateCommand::from($request);
        $result  = $this->service->create($command);

        return BrandResource::make($result);
    }

    public function update($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = BrandUpdateCommand::from($request);
        $this->service->update($command);

        return static::success();

    }

    public function destroy($id, Request $request) : JsonResponse
    {
        $request->offsetSet('id', $id);

        $command = BrandDeleteCommand::from($request);
        $this->service->delete($command);

        return static::success();
    }
}
