<?php

namespace RedJasmine\Region\UI\Http\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Region\Application\Services\Country\CountryService;
use RedJasmine\Region\Application\Services\Country\Queries\CountryPaginateQuery;
use RedJasmine\Region\UI\Http\Api\Resources\CountryResource;

class CountryController extends Controller
{
    public function __construct(
        protected CountryService $service
    ) {
    }

    /**
     * 获取国家列表（分页）
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = CountryPaginateQuery::from($request->all());
        $locale = $request->input('locale', app()->getLocale());
        
        $paginator = $this->service->paginate($query, $locale);
        
        return CountryResource::collection($paginator);
    }

    /**
     * 获取单个国家信息
     */
    public function show(string $code, Request $request): CountryResource|JsonResponse
    {
        $locale = $request->input('locale', app()->getLocale());
        
        $country = $this->service->find($code, $locale);
        
        if (!$country) {
            return response()->json([
                'message' => '国家不存在'
            ], 404);
        }
        
        return new CountryResource($country);
    }
}