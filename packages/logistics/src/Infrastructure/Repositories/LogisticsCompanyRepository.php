<?php

namespace RedJasmine\Logistics\Infrastructure\Repositories;

use RedJasmine\Logistics\Domain\Models\LogisticsCompany;
use RedJasmine\Logistics\Domain\Repositories\LogisticsCompanyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 物流公司仓库实现
 *
 * 基于Repository实现，提供物流公司实体的读写操作能力
 *
 * @method LogisticsCompany find($id)
 */
class LogisticsCompanyRepository extends Repository implements LogisticsCompanyRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = LogisticsCompany::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::partial('name'),
            AllowedFilter::exact('code'),
            AllowedFilter::exact('status'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('name'),
            AllowedSort::field('code'),
            AllowedSort::field('sort'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [];
    }
}
