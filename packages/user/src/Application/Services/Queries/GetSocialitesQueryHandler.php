<?php

namespace RedJasmine\User\Application\Services\Queries;

use RedJasmine\Support\Application\QueryHandlers\QueryHandler;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\User\Application\Services\UserQueryService;
use RedJasmine\User\Domain\Services\UserSocialiteService;

class GetSocialitesQueryHandler extends QueryHandler
{


    public function __construct(
        protected UserQueryService $service,
        protected UserSocialiteService $userSocialiteService

    ) {
    }

    public function handle(GetSocialitesQuery $query)
    {


        $user = $this->service->repository->findById(FindQuery::from(['id' => $query->id]));

        return $this->userSocialiteService->getBinds($user);
    }
}