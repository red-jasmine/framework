<?php

namespace RedJasmine\Socialite\Infrastructure\ReadRepositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserReadRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
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

    public function queryUsers(UserInterface $owner, string $provider) : Collection
    {
        return $this->query(null)->onlyOwner($owner)
                    ->where(['provider' => $provider])
                    ->get();
    }


}
