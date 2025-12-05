<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Repositories\PromoterRepositoryInterface;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 推广员仓库实现
 *
 * 基于Repository实现，提供推广员实体的读写操作能力
 */
class PromoterRepository extends Repository implements PromoterRepositoryInterface
{
    protected static string $modelClass = Promoter::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('level'),
            AllowedFilter::exact('group_id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('team_id'),
            AllowedFilter::partial('owner_id'),
            AllowedFilter::callback('search', static function (Builder $builder, $value) {
                return $builder->where(function (Builder $builder) use ($value) {
                    $builder->where('name', 'like', '%'.$value.'%')
                            ->orWhere('owner_id', 'like', '%'.$value.'%');
                });
            })
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('level'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'team', 'parent', 'group', 'promoterLevel'
        ];
    }

    public function findByOwner(UserInterface|Query $owner): ?Promoter
    {
        if ($owner instanceof Query) {
            return $this->query()
                        ->onlyOwner($owner->owner)
                        ->firstOrFail();
        }
        
        return static::$modelClass::onlyOwner($owner)->first();
    }
}
