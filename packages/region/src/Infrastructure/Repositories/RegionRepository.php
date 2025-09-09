<?php

namespace RedJasmine\Region\Infrastructure\Repositories;

use RedJasmine\Region\Domain\Models\Region;
use RedJasmine\Region\Domain\Repositories\RegionRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Support\Infrastructure\Repositories\HasTree;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 地区仓库实现
 *
 * 基于Repository实现，提供地区实体的读写操作能力
 */
class RegionRepository extends Repository implements RegionRepositoryInterface
{
    use HasTree;

    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Region::class;

    /**
     * 默认排序
     */
    protected mixed $defaultSort = '-code';

    /**
     * 获取树形结构数据
     */
    public function tree(?Query $query = null): array
    {
        $nodes = $this->queryBuilder($query)->select($this->baseFields())->get();
        $model = (new static::$modelClass);
        return $model->toTree($nodes);
    }

    /**
     * 获取子级数据
     */
    public function children(?Query $query): array
    {
        return $this->queryBuilder($query)
                    ->select($this->baseFields())
                    ->get()->toArray();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('country_code'),
            AllowedFilter::exact('parent_code'),
            AllowedFilter::exact('code'),
            AllowedFilter::exact('type'),
            AllowedFilter::scope('level'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return ['code'];
    }

    /**
     * 基础字段
     */
    protected function baseFields(): array
    {
        return [
            'code',
            'parent_code',
            'name',
            'type',
            'level'
        ];
    }
}
