<?php

namespace RedJasmine\Announcement\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Announcement\Domain\Repositories\AnnouncementReadRepositoryInterface;
use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Announcement\Domain\Models\Enums\AnnouncementStatus;
use RedJasmine\Announcement\Domain\Models\Enums\ApprovalStatus;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class AnnouncementReadRepository extends QueryBuilderReadRepository implements AnnouncementReadRepositoryInterface
{
    public static $modelClass = Announcement::class;

    /**
     * 允许的过滤器配置
     */
    public function allowedFilters() : array
    {
        return [
            AllowedFilter::partial('title'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('biz'),
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('approval_status'),
            AllowedFilter::exact('is_force_read'),
            AllowedFilter::scope('published'),
            AllowedFilter::scope('draft'),
            AllowedFilter::scope('revoked'),
            AllowedFilter::scope('pending_approval'),
            AllowedFilter::callback('owner', fn(Builder $builder, $value) => $builder->onlyOwner(
                is_array($value) ? UserData::from($value) : $value
            )),
        ];
    }

    /**
     * 允许的排序字段配置
     */
    public function allowedSorts() : array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('title'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('publish_time'),
            AllowedSort::field('sort'),
        ];
    }

    /**
     * 允许包含的关联配置
     */
    public function allowedIncludes() : array
    {
        return [
            'category',
            'category.parent',
        ];
    }

    /**
     * 根据业务线和所有者查找公告
     */
    public function findByBizAndOwner(string $biz, string $ownerType, string $ownerId) : Collection
    {
        return $this->query()
                    ->where('biz', $biz)
                    ->where('owner_type', $ownerType)
                    ->where('owner_id', $ownerId)
                    ->get();
    }

    /**
     * 根据分类查找公告
     */
    public function findByCategory(int $categoryId) : Collection
    {
        return $this->query()
                    ->where('category_id', $categoryId)
                    ->get();
    }

    /**
     * 根据状态查找公告
     */
    public function findByStatus(string $status) : Collection
    {
        return $this->query()
                    ->where('status', $status)
                    ->get();
    }

    /**
     * 根据审批状态查找公告
     */
    public function findByApprovalStatus(string $approvalStatus) : Collection
    {
        return $this->query()
                    ->where('approval_status', $approvalStatus)
                    ->get();
    }

    /**
     * 查找已发布的公告
     */
    public function findPublished() : Collection
    {
        return $this->query()
                    ->where('status', AnnouncementStatus::PUBLISHED)
                    ->get();
    }

    /**
     * 查找待审批的公告
     */
    public function findPendingApproval() : Collection
    {
        return $this->query()
                    ->where('approval_status', ApprovalStatus::PENDING)
                    ->get();
    }
}
