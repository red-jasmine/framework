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

    public function getUsersByOwner(UserInterface $owner, string $appId, ?string $provider = null) : Collection
    {
        return $this->query()
                    ->onlyOwner($owner)
                    ->where(['app_id' => $appId])
                    ->when($provider, function ($query, $provider) {
                        $query->where(['provider' => $provider]);
                    })
                    ->get();
    }


}
