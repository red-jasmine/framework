<?php

namespace RedJasmine\Announcement\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
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

    /**
     * 根据查询条件查找单个公告
     */
    public function findByQuery(FindQuery $query) : ?Announcement;

    /**
     * 分页查询公告
     */
    public function paginate(PaginateQuery $query) : LengthAwarePaginator;

    /**
     * 设置查询作用域
     */
    public function withQuery(\Closure $closure) : static;
}
