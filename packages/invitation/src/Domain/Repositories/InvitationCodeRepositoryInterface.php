<?php

namespace RedJasmine\Invitation\Domain\Repositories;

use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 邀请码仓库接口
 *
 * 提供邀请码实体的读写操作统一接口
 *
 * @method InvitationCode find($id)
 */
interface InvitationCodeRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code): ?InvitationCode;

    /**
     * 检查邀请码是否存在
     */
    public function existsByCode(string $code): bool;

    /**
     * 获取用户的邀请码统计
     * 合并了原InvitationCodeReadRepositoryInterface中的方法
     */
    public function getUserInvitationStatistics($userId, $userType): array;

    /**
     * 获取邀请码使用排行
     */
    public function getUsageRanking(int $limit = 10): array;

    /**
     * 获取邀请统计
     */
    public function getInvitationStats(int $inviterId, string $inviterType): array;

    /**
     * 获取热门邀请码
     */
    public function getPopularCodes(int $limit = 10): array;
}
