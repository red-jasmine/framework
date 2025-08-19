<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

/**
 * 消息只读仓库接口
 */
interface MessageReadRepositoryInterface extends ReadRepositoryInterface
{

    /**
     * 获取未读消息数量
     * @param  UserInterface  $owner
     * @param  string  $biz
     *
     * @return int
     */
    public function getUnreadCount(UserInterface $owner, string $biz) : int;
}
