<?php

namespace RedJasmine\Vip\Application\Services\Queries;

use RedJasmine\Support\Application\QueryHandlers\QueryHandler;
use RedJasmine\Vip\Application\Services\UserVipQueryService;
use RedJasmine\Vip\Domain\Models\UserVip;

class FindUserVipQueryHandle extends QueryHandler
{

    public function __construct(
        protected UserVipQueryService $service
    ) {
    }

    public function handle(FindUserVipQuery $query) : ?UserVip
    {
        return $this->service->getRepository()->findVipByOwner($query->owner, $query->appId, $query->type)

               ?? UserVip::make([
                'app_id'     => $query->appId,
                'type' => $query->type,
                'is_forever' => false,
                'level'      => 0
            ]);
    }

}