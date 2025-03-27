<?php

namespace RedJasmine\Vip\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Vip\Application\Services\Queries\FindUserVipQuery;
use RedJasmine\Vip\Application\Services\UserVipApplicationService;
use RedJasmine\Vip\UI\Http\User\Api\Resources\UserVipResource;

class UserVipController extends Controller
{
    public function __construct(
        protected UserVipApplicationService $service,
    ) {

        $this->service->readRepository->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    // 我的会员


    public function vips(Request $request) : AnonymousResourceCollection
    {
        $query = PaginateQuery::from($request);

        $result = $this->service->paginate($query);
        return UserVipResource::collection($result);
    }

    public function vip(string $appId, string $type) : UserVipResource
    {
        $query = FindUserVipQuery::from([
            'appId' => $appId,
            'type'  => $type,
            'owner' => $this->getOwner(),
        ]);

        $vip = $this->service->findUserVip($query);

        return new  UserVipResource($vip);

    }
}