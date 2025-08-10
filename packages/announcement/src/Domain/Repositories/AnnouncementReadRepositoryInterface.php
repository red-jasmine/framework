<?php

namespace RedJasmine\Announcement\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface AnnouncementReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 根据业务线和所有者查找公告
     */
    public function findByBizAndOwner(string $biz, string $ownerType, string $ownerId): \Illuminate\Database\Eloquent\Collection;

    /**
     * 根据分类查找公告
     */
    public function findByCategory(int $categoryId): \Illuminate\Database\Eloquent\Collection;

    /**
     * 根据状态查找公告
     */
    public function findByStatus(string $status): \Illuminate\Database\Eloquent\Collection;

    /**
     * 根据审批状态查找公告
     */
    public function findByApprovalStatus(string $approvalStatus): \Illuminate\Database\Eloquent\Collection;

    /**
     * 查找已发布的公告
     */
    public function findPublished(): \Illuminate\Database\Eloquent\Collection;

    /**
     * 查找待审批的公告
     */
    public function findPendingApproval(): \Illuminate\Database\Eloquent\Collection;
}
