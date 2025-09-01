<?php

namespace RedJasmine\Promotion\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Models\Enums\ActivityStatusEnum;
use RedJasmine\Promotion\Domain\Repositories\ActivityReadRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 活动只读仓库实现
 */
class ActivityReadRepository extends QueryBuilderReadRepository implements ActivityReadRepositoryInterface
{
    public static string $modelClass = Activity::class;
    
    protected mixed $defaultSort = '-created_at';
    
    /**
     * 允许的过滤器配置
     */
    public function allowedFilters(): array
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
     * 允许的排序字段配置
     */
    public function allowedSorts(): array
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
     * 允许包含的关联配置
     */
    public function allowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('products'),
            AllowedInclude::relationship('participations'),
            AllowedInclude::relationship('owner'),
        ];
    }
    
    public function byType(string $type): static
    {
        return $this->withQuery(function (Builder $query) use ($type) {
            $query->where('type', $type);
        });
    }
    
    public function running(): static
    {
        return $this->withQuery(function (Builder $query) {
            $query->where('status', ActivityStatusEnum::RUNNING)
                  ->where('start_time', '<=', now())
                  ->where('end_time', '>', now());
        });
    }
    
    public function upcoming(int $minutes = 60): static
    {
        return $this->withQuery(function (Builder $query) use ($minutes) {
            $query->where('status', ActivityStatusEnum::PENDING)
                  ->where('start_time', '>', now())
                  ->where('start_time', '<=', now()->addMinutes($minutes));
        });
    }
    
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
    
    public function byProduct(int $productId): static
    {
        return $this->withQuery(function (Builder $query) use ($productId) {
            $query->whereHas('products', function (Builder $subQuery) use ($productId) {
                $subQuery->where('product_id', $productId);
            });
        });
    }
    
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
