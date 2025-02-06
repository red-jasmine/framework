<?php

namespace RedJasmine\Socialite\Application\Services\Queries;


use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Socialite\Application\Services\SocialiteUserQueryService;
use RedJasmine\Support\Application\QueryHandlers\QueryHandler;

class GetUsersByOwnerQueryHandler extends QueryHandler
{

    public function __construct(
        protected SocialiteUserQueryService $service

    ) {
    }


    public function handle(GetUsersByOwnerQuery $query) : Collection
    {

        return $this->service->repository->getUsersByOwner($query->owner, $query->appId, $query->provider);
    }
}