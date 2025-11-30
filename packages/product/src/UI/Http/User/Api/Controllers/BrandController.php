<?php

namespace RedJasmine\Product\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Product\Application\Brand\Services\BrandApplicationService;
use RedJasmine\Product\UI\Http\User\Api\Resources\BrandResource;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class BrandController extends Controller
{
    public function __construct(
        protected BrandApplicationService $service
    ) {

    }


    public function index(Request $request) : AnonymousResourceCollection
    {
        $locale = $request->get('locale', app()->getLocale());
        
        // 预加载翻译数据
        $this->service->repository->withQuery(function ($query) use ($locale) {
            $query->withTranslation($locale);
        });

        $result = $this->service->paginate(PaginateQuery::from($request));
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
}
