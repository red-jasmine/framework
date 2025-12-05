<?php

namespace RedJasmine\Socialite\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserRepositoryInterface;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

/**
 * 社交用户仓库实现
 *
 * 基于Repository实现，提供社交用户实体的读写操作能力
 */
class SocialiteUserRepository extends Repository implements SocialiteUserRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = SocialiteUser::class;

    /**
     * 根据查询条件查找用户
     */
    public function findUser(SocialiteUserFindUserQuery $query): ?SocialiteUser
    {
        return $this->query()->where(
            [
                'client_id' => $query->clientId,
                'provider'  => $query->provider,
                'identity'  => $query->identity,
                'app_id'    => $query->appId,
            ]
        )->first();
    }

    /**
     * 根据所有者获取用户列表
     */
    public function getUsersByOwner(UserInterface $owner, string $appId, ?string $provider = null): Collection
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
