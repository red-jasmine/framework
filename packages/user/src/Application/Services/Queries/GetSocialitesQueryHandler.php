<?php

namespace RedJasmine\User\Application\Services\Queries;

use RedJasmine\Support\Application\Queries\QueryHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\User\Application\Services\BaseUserApplicationService;
use RedJasmine\User\Domain\Services\UserSocialiteService;

class GetSocialitesQueryHandler extends QueryHandler
{


    public function __construct(
        protected BaseUserApplicationService $service,
        protected UserSocialiteService $userSocialiteService

    ) {
    }

    public function handle(GetSocialitesQuery $query)
    {
        $user = $this->service->readRepository->find(FindQuery::from(['id' => $query->id]));


        return $this->userSocialiteService->getBinds($user);
    }
}