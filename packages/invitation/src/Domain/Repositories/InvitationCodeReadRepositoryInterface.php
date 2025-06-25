<?php

namespace RedJasmine\Invitation\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

/**
 * 邀请码只读仓库接口
 */
interface InvitationCodeReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code);

    /**
     * 获取用户的邀请码统计
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