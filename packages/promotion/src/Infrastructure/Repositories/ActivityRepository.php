<?php

namespace RedJasmine\Promotion\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;
use RedJasmine\Promotion\Domain\Repositories\ActivityRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 活动仓库实现
 *
 * 基于Repository实现，提供活动实体的读写操作能力
 */
class ActivityRepository extends Repository implements ActivityRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Activity::class;

    /**
     * 默认排序
     */
    protected mixed $defaultSort = '-created_at';

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::partial('title'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::scope('running'),
            AllowedFilter::scope('upcoming'),
            AllowedFilter::scope('expired'),
            AllowedFilter::callback('start_time_from', function (Builder $query, $value) {
                return $query->where('start_time', '>=', $value);
            }),
            AllowedFilter::callback('start_time_to', function (Builder $query, $value) {
                return $query->where('start_time', '<=', $value);
            }),
            AllowedFilter::callback('end_time_from', function (Builder $query, $value) {
                return $query->where('end_time', '>=', $value);
            }),
            AllowedFilter::callback('end_time_to', function (Builder $query, $value) {
                return $query->where('end_time', '<=', $value);
            }),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('title'),
            AllowedSort::field('type'),
            AllowedSort::field('status'),
            AllowedSort::field('start_time'),
            AllowedSort::field('end_time'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('total_participants'),
            AllowedSort::field('total_sales'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'products',
            'participations',
            'owner',
        ];
    }

    /**
     * 根据类型查找活动
     */
    public function findByType(string $type): Collection
    {
        return $this->query()->where('type', $type)->get();
    }

    /**
     * 查找正在进行的活动
     */
    public function findRunningActivities(): Collection
    {
        return $this->query()
            ->where('status', ActivityStatusEnum::RUNNING)
            ->where('start_time', '<=', now())
            ->where('end_time', '>', now())
            ->get();
    }

    /**
     * 查找即将开始的活动
     */
    public function findUpcomingActivities(int $minutes = 60): Collection
    {
        return $this->query()
            ->where('status', ActivityStatusEnum::PENDING)
            ->where('start_time', '>', now())
            ->where('start_time', '<=', now()->addMinutes($minutes))
            ->get();
    }

    /**
     * 查找已过期但未结束的活动
     */
    public function findExpiredActivities(): Collection
    {
        return $this->query()
            ->where('status', ActivityStatusEnum::RUNNING)
            ->where('end_time', '<', now())
            ->get();
    }

    /**
     * 根据活动类型查询
     */
    public function byType(string $type): static
    {
        return $this->withQuery(function (Builder $query) use ($type) {
            $query->where('type', $type);
        });
    }

    /**
     * 查询正在进行的活动
     */
    public function running(): static
    {
        return $this->withQuery(function (Builder $query) {
            $query->where('status', ActivityStatusEnum::RUNNING)
                  ->where('start_time', '<=', now())
                  ->where('end_time', '>', now());
        });
    }

    /**
     * 查询即将开始的活动
     */
    public function upcoming(int $minutes = 60): static
    {
        return $this->withQuery(function (Builder $query) use ($minutes) {
            $query->where('status', ActivityStatusEnum::PENDING)
                  ->where('start_time', '>', now())
                  ->where('start_time', '<=', now()->addMinutes($minutes));
        });
    }

    /**
     * 查询用户可参与的活动
     */
    public function availableForUser($user): static
    {
        return $this->withQuery(function (Builder $query) use ($user) {
            $query->where('status', ActivityStatusEnum::RUNNING)
                  ->where('is_show', true)
                  ->where('start_time', '<=', now())
                  ->where('end_time', '>', now())
                  ->where(function (Builder $subQuery) use ($user) {
                      // 检查用户要求
                      $subQuery->whereNull('user_requirements')
                               ->orWhereJsonDoesntContain('user_requirements->exclude_users', $user->getID());
                  });
        });
    }

    /**
     * 查询商品相关的活动
     */
    public function byProduct(int $productId): static
    {
        return $this->withQuery(function (Builder $query) use ($productId) {
            $query->whereHas('products', function (Builder $subQuery) use ($productId) {
                $subQuery->where('product_id', $productId);
            });
        });
    }

    /**
     * 查询分类相关的活动
     */
    public function byCategory(int $categoryId): static
    {
        return $this->withQuery(function (Builder $query) use ($categoryId) {
            $query->whereJsonContains('product_requirements->categories', $categoryId)
                  ->orWhereHas('products.product', function (Builder $subQuery) use ($categoryId) {
                      $subQuery->where('category_id', $categoryId);
                  });
        });
    }
}
