<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Repositories;

use RedJasmine\Invitation\Domain\Models\InvitationCode;
use RedJasmine\Invitation\Domain\Models\ValueObjects\Inviter;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 邀请码仓库接口
 */
interface InvitationCodeRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据邀请码查找
     */
    public function findByCode(string $code): ?InvitationCode;

    /**
     * 根据邀请人查找邀请码列表
     */
    public function findByInviter(Inviter $inviter): array;

    /**
     * 根据邀请人查找有效的邀请码
     */
    public function findActiveByInviter(\RedJasmine\Support\Contracts\UserInterface $inviter): ?InvitationCode;

    /**
     * 检查邀请码是否存在
     */
    public function existsByCode(string $code): bool;

    /**
     * 根据ID查找邀请码
     */
    public function findById(int $id): ?InvitationCode;

    /**
     * 保存邀请码
     */
    public function save(InvitationCode $invitationCode): void;

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
     * 批量更新邀请码状态
     */
    public function batchUpdateStatus(array $ids, string $status): void;

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
} 