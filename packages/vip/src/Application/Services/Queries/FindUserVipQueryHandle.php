<?php

namespace RedJasmine\Vip\Application\Services\Queries;

use RedJasmine\Support\Application\Queries\QueryHandler;
use RedJasmine\Vip\Application\Services\UserVipApplicationService;
use RedJasmine\Vip\Domain\Models\UserVip;

class FindUserVipQueryHandle extends QueryHandler
{

    public function __construct(
        protected UserVipApplicationService $service
    ) {
    }

    public function handle(FindUserVipQuery $query) : ?UserVip
    {
        return $this->service->repository->findVipByOwner($query->owner, $query->biz, $query->type)

               ?? UserVip::make([
                'biz'     => $query->biz,
                'type'       => $query->type,
                'is_forever' => false,
                'level'      => 0
            ]);
    }

}