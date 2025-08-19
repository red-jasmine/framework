<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 消息写操作仓库接口
 */
interface MessageRepositoryInterface extends RepositoryInterface
{


    /**
     * 批量标记消息为已读
     */
    public function markAsRead(array $messageIds, string $bid, UserInterface $owner) : int;

    /**
     * 全部标记已读
     * @param  string  $bid
     * @param  UserInterface  $owner
     *
     * @return int
     */
    public function allMarkAsReadAll(string $bid, UserInterface $owner) : int;

}
