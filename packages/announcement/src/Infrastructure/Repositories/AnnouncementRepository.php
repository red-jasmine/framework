<?php

namespace RedJasmine\Announcement\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Announcement\Domain\Models\Enums\AnnouncementStatus;
use RedJasmine\Announcement\Domain\Repositories\AnnouncementRepositoryInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class AnnouncementRepository extends Repository implements AnnouncementRepositoryInterface
{
    protected static string $modelClass = Announcement::class;
    
    protected ?\Closure $queryCallback = null;

    /**
     * 根据业务线和所有者查找公告
     */
    public function findByBizAndOwner(string $biz, string $ownerType, string $ownerId): Collection
    {
        return $this->applyQueryScope(static::$modelClass::where('biz', $biz)
                                  ->where('owner_type', $ownerType)
                                  ->where('owner_id', $ownerId))
                    ->get();
    }

    /**
     * 根据分类查找公告
     */
    public function findByCategory(int $categoryId): Collection
    {
        return $this->applyQueryScope(static::$modelClass::where('category_id', $categoryId))
                    ->get();
    }

    /**
     * 根据状态查找公告
     */
    public function findByStatus(string $status): Collection
    {
        return $this->applyQueryScope(static::$modelClass::where('status', $status))
                    ->get();
    }

    /**
     * 根据审批状态查找公告
     */
    public function findByApprovalStatus(string $approvalStatus): Collection
    {
        return $this->applyQueryScope(static::$modelClass::where('approval_status', $approvalStatus))
                    ->get();
    }

    /**
     * 查找已发布的公告
     */
    public function findPublished(): Collection
    {
        return $this->applyQueryScope(static::$modelClass::where('status', AnnouncementStatus::PUBLISHED))
                    ->get();
    }

    /**
     * 查找待审批的公告
     */
    public function findPendingApproval(): Collection
    {
        return $this->applyQueryScope(static::$modelClass::where('approval_status', ApprovalStatusEnum::PENDING))
                    ->get();
    }

    /**
     * 根据查询条件查找单个公告
     */
    public function findByQuery(FindQuery $query): ?Announcement
    {
        $builder = $this->createQueryBuilder($query);
        return $builder->first();
    }

    /**
     * 分页查询公告
     */
    public function paginate(PaginateQuery $query): LengthAwarePaginator
    {
        $builder = $this->createQueryBuilder($query);
        return $builder->paginate($query->perPage ?? 15);
    }

    /**
     * 设置查询作用域
     */
    public function withQuery(\Closure $closure): static
    {
        $this->queryCallback = $closure;
        return $this;
    }

    /**
     * 创建查询构建器
     */
    protected function createQueryBuilder($query): QueryBuilder
    {
        $queryBuilder = QueryBuilder::for(static::$modelClass)
            ->allowedFilters($this->allowedFilters())
            ->allowedSorts($this->allowedSorts())
            ->allowedIncludes($this->allowedIncludes());

        // 应用查询作用域
        if ($this->queryCallback) {
            $queryBuilder->tap($this->queryCallback);
        }

        return $queryBuilder;
    }

    /**
     * 应用查询作用域
     */
    protected function applyQueryScope(Builder $builder): Builder
    {
        if ($this->queryCallback) {
            ($this->queryCallback)($builder);
        }
        return $builder;
    }


    protected function allowedFilters(?Query $query = null): array
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


    protected function allowedSorts($query = null): array
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
     *
     * @param  Query|null  $query
     *
     * @return array
     */
    protected function allowedIncludes(?Query $query = null): array
    {
        return [
            'category',
            'category.parent',
        ];
    }
}
