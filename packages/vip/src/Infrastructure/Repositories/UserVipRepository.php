<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\UserVipRepositoryInterface;

/**
 * 用户VIP仓库实现
 *
 * 基于Repository实现，提供用户VIP实体的读写操作能力
 */
class UserVipRepository extends Repository implements UserVipRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = UserVip::class;

    /**
     * 根据用户、业务类型和VIP类型查找用户VIP
     */
    public function findVipByOwner(UserInterface $owner, string $biz, string $type): ?UserVip
    {
        return $this->query()
            ->where('owner_type', $owner->getType())
            ->where('owner_id', $owner->getID())
            ->where('biz', $biz)
            ->where('type', $type)
            ->first();
    }
}