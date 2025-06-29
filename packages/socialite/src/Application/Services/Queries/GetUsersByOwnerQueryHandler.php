<?php

namespace RedJasmine\Socialite\Application\Services\Queries;


use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Socialite\Application\Services\SocialiteUserApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class GetUsersByOwnerQueryHandler extends QueryHandler
{

    public function __construct(
        protected SocialiteUserApplicationService $service

    ) {
    }


    public function handle(GetUsersByOwnerQuery $query) : Collection
    {

        return $this->service->repository->getUsersByOwner($query->owner, $query->appId, $query->provider);
    }
}