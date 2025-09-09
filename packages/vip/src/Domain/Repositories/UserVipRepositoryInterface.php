<?php

namespace RedJasmine\Vip\Domain\Repositories;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Vip\Domain\Models\UserVip;

/**
 * 用户VIP仓库接口
 *
 * 提供用户VIP实体的读写操作统一接口
 *
 * @method UserVip find($id)
 */
interface UserVipRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据用户、业务类型和VIP类型查找用户VIP
     */
    public function findVipByOwner(UserInterface $owner, string $biz, string $type): ?UserVip;
}