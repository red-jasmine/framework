<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 消息仓库接口
 *
 * 提供消息实体的读写操作统一接口
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

    /**
     * 获取未读消息数量
     *
     *
     * @param  UserInterface  $owner
     * @param  string  $biz
     *
     * @return int
     */
    public function getUnreadCount(UserInterface $owner, string $biz) : int;

    /**
     * 获取未读消息统计
     * @param  UserInterface  $owner
     * @param  string  $biz
     *
     * @return array<int,int>
     */
    public function getUnreadStatistics(UserInterface $owner, string $biz) : array;
}
