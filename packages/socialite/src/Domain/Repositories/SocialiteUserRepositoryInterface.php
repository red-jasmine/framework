<?php

namespace RedJasmine\Socialite\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Socialite\Domain\Models\SocialiteUser;
use RedJasmine\Socialite\Domain\Repositories\Queries\SocialiteUserFindUserQuery;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 社交用户仓库接口
 *
 * 提供社交用户实体的读写操作统一接口
 *
 * @method SocialiteUser find($id)
 */
interface SocialiteUserRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据查询条件查找用户
     */
    public function findUser(SocialiteUserFindUserQuery $query): ?SocialiteUser;

    /**
     * 根据所有者获取用户列表
     */
    public function getUsersByOwner(UserInterface $owner, string $appId, ?string $provider = null): Collection;
}
