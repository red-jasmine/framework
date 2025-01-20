<?php

namespace RedJasmine\Socialite\Infrastructure\ReadRepositories;

use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class SocialiteUserReadRepository extends QueryBuilderReadRepository implements SocialiteUserReadRepositoryInterface
{


    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = SocialiteUser::class;

    public function findUser(SocialiteUserFindUserQuery $query) : ?SocialiteUser
    {
        return $this->query(null)->where(
            [
                'client_id' => $query->clientId,
                'provider'  => $query->provider,
                'identity'  => $query->identity,
                'app_id'    => $query->appId,
            ]
        )->first();
    }


}
