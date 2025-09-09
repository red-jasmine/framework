<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Vip\Domain\Models\Vip;
use RedJasmine\Vip\Domain\Repositories\VipRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * VIP仓库实现
 *
 * 基于Repository实现，提供VIP实体的读写操作能力
 */
class VipRepository extends Repository implements VipRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Vip::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('biz'),
            AllowedFilter::exact('type'),
        ];
    }

    /**
     * 根据业务类型和VIP类型查找
     */
    public function findVipType(string $biz, string $type): ?Vip
    {
        return $this->query()->where('biz', $biz)->where('type', $type)->first();
    }
}