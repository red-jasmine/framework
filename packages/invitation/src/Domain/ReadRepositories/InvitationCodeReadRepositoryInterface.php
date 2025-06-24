<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\ReadRepositories;

use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Models\ValueObjects\Inviter;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

/**
 * 邀请码只读仓库接口
 */
interface InvitationCodeReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 根据ID查找邀请码
     */
    public function findById(int $id): ?InvitationCode;

    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code): ?InvitationCode;

    /**
     * 根据邀请人查找邀请码列表
     */
    public function findByInviter(Inviter $inviter): array;

    /**
     * 检查邀请码是否存在
     */
    public function existsByCode(string $code): bool;

    /**
     * 分页查询邀请码（自定义方法，不与基类冲突）
     */
    public function paginateInvitationCodes(array $filters = [], int $page = 1, int $pageSize = 20): array;

    /**
     * 获取即将过期的邀请码
     */
    public function getExpiringSoon(int $hours = 24): array;

    /**
     * 获取已过期的邀请码
     */
    public function getExpired(): array;

    /**
     * 根据标签查找邀请码
     */
    public function findByTag(string $tagName, string $tagValue): array;

    /**
     * 统计邀请码数量
     */
    public function countByInviter(Inviter $inviter): int;

    /**
     * 获取热门邀请码
     */
    public function getPopular(int $limit = 10): array;

    /**
     * 统计邀请码总数
     */
    public function count(array $filters = []): int;

    /**
     * 获取邀请码统计数据
     */
    public function getStatistics(array $filters = []): array;

    /**
     * 根据状态统计数量
     */
    public function countByStatus(): array;

    /**
     * 获取今日新增数量
     */
    public function getTodayCount(): int;

    /**
     * 获取使用排行榜
     */
    public function getUsageRanking(int $limit = 10): array;
} 