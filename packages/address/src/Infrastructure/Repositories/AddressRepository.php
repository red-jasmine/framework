<?php

namespace RedJasmine\Address\Infrastructure\Repositories;

use RedJasmine\Address\Domain\Models\Address;
use RedJasmine\Address\Domain\Repositories\AddressRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 地址仓库实现
 *
 * 基于BaseRepository实现，提供地址实体的读写操作能力
 */
class AddressRepository extends BaseRepository implements AddressRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $eloquentModelClass = Address::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('type'),
            AllowedFilter::exact('status'),
        ];
    }
}
