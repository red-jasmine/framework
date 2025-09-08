<?php

namespace RedJasmine\Announcement\Infrastructure\Repositories\Eloquent;

use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Announcement\Domain\Models\Enums\AnnouncementStatus;
use RedJasmine\Announcement\Domain\Models\Enums\ApprovalStatus;
use RedJasmine\Announcement\Domain\Repositories\AnnouncementRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class AnnouncementRepository extends Repository implements AnnouncementRepositoryInterface
{
    protected static string $modelClass = Announcement::class;

    /**
     * 根据业务线和所有者查找公告
     */
    public function findByBizAndOwner(string $biz, string $ownerType, string $ownerId): \Illuminate\Database\Eloquent\Collection
    {
        return static::$modelClass::where('biz', $biz)
                                  ->where('owner_type', $ownerType)
                                  ->where('owner_id', $ownerId)
                                  ->get();
    }

    /**
     * 根据分类查找公告
     */
    public function findByCategory(int $categoryId): \Illuminate\Database\Eloquent\Collection
    {
        return static::$modelClass::where('category_id', $categoryId)->get();
    }

    /**
     * 根据状态查找公告
     */
    public function findByStatus(string $status): \Illuminate\Database\Eloquent\Collection
    {
        return static::$modelClass::where('status', $status)->get();
    }

    /**
     * 根据审批状态查找公告
     */
    public function findByApprovalStatus(string $approvalStatus): \Illuminate\Database\Eloquent\Collection
    {
        return static::$modelClass::where('approval_status', $approvalStatus)->get();
    }

    /**
     * 查找已发布的公告
     */
    public function findPublished(): \Illuminate\Database\Eloquent\Collection
    {
        return static::$modelClass::where('status', AnnouncementStatus::PUBLISHED)->get();
    }

    /**
     * 查找待审批的公告
     */
    public function findPendingApproval(): \Illuminate\Database\Eloquent\Collection
    {
        return static::$modelClass::where('approval_status', ApprovalStatus::PENDING)->get();
    }
}
