<?php

namespace RedJasmine\Vip\UI\Http\User\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Vip\Application\Services\Commands\UserPurchaseVipCommand;
use RedJasmine\Vip\Application\Services\Queries\Products\VipProductPaginateQuery;
use RedJasmine\Vip\Application\Services\VipProductApplicationService;
use RedJasmine\Vip\Application\Services\VipPurchaseCommandService;
use RedJasmine\Vip\UI\Http\User\Api\Resources\VipProductResource;

class VipProductController extends Controller
{

    public function __construct(
        public VipProductApplicationService $service,
        public VipPurchaseCommandService $vipPurchaseCommand
    ) {
    }

    public function index(Request $request) : AnonymousResourceCollection
    {

        $query         = VipProductPaginateQuery::from($request);
        $query->status = 'on_sale,sold_out';

        $result = $this->service->paginate($query);
        return VipProductResource::collection($result);
    }

    public function show($id, Request $request) : VipProductResource
    {
        $query = FindQuery::from($request);
        $query->setKey($id);
        $result = $this->service->find($query);
        return VipProductResource::make($result);
    }

    public function buy(Request $request) : JsonResponse
    {
        // 购买商品会员

        $request->offsetSet('owner', $this->getOwner());

        $result = $this->vipPurchaseCommand->buy(UserPurchaseVipCommand::from($request));

        return static::success($result->toArray());

    }
}
