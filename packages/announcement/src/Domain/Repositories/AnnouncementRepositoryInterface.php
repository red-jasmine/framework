<?php

namespace RedJasmine\Announcement\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Announcement\Domain\Models\Announcement;

/**
 * @method Announcement  find($id)
 */
interface AnnouncementRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据业务线和所有者查找公告
     */
    public function findByBizAndOwner(string $biz, string $ownerType, string $ownerId) : Collection;

    /**
     * 根据分类查找公告
     */
    public function findByCategory(int $categoryId) : Collection;

    /**
     * 根据状态查找公告
     */
    public function findByStatus(string $status) : Collection;

    /**
     * 根据审批状态查找公告
     */
    public function findByApprovalStatus(string $approvalStatus) : Collection;

    /**
     * 查找已发布的公告
     */
    public function findPublished() : Collection;

    /**
     * 查找待审批的公告
     */
    public function findPendingApproval() : Collection;
}
