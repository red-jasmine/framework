<?php

namespace RedJasmine\Card\Infrastructure\Repositories;

use RedJasmine\Card\Domain\Models\Card;
use RedJasmine\Card\Domain\Repositories\CardRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 卡密仓库实现
 *
 * 基于Repository实现，提供卡密实体的读写操作能力
 */
class CardRepository extends Repository implements CardRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Card::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('is_loop'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('group_id'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null) : array
    {
        return ['group'];
    }
}
