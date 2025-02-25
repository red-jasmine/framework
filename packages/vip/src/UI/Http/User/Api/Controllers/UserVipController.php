<?php

namespace RedJasmine\Vip\UI\Http\User\Api\Controllers;

use Illuminate\Http\Request;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Vip\Application\Services\Queries\FindUserVipQuery;
use RedJasmine\Vip\Application\Services\UserVipQueryService;
use RedJasmine\Vip\UI\Http\User\Api\Resources\UserVipResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserVipController extends Controller
{
    public function __construct(
        protected UserVipQueryService $queryService,
    ) {

        $this->queryService->getRepository()->withQuery(function ($query) {
            $query->onlyOwner($this->getOwner());
        });
    }

    // 我的会员


    public function vips(Request $request) : AnonymousResourceCollection
    {
        $query = PaginateQuery::from($request);

        $result = $this->queryService->paginate($query);
        return UserVipResource::collection($result);
    }

    public function vip(string $appId, string $type) : UserVipResource
    {
        $query = FindUserVipQuery::from([
            'appId' => $appId,
            'type'  => $type,
            'owner' => $this->getOwner(),
        ]);

        $vip = $this->queryService->findUserVip($query);

        return new  UserVipResource($vip);

    }
}