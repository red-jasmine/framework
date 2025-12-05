<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 钱包仓库实现
 *
 * 基于Repository实现，提供钱包实体的读写操作能力
 */
class WalletRepository extends Repository implements WalletRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Wallet::class;

    /**
     * 查找并锁定钱包
     */
    public function findLock($id) : Wallet
    {
        return static::$modelClass::query()->lockForUpdate()->findOrFail($id);
    }

    /**
     * 根据所有者和类型查找钱包
     */
    public function findByOwnerType(UserInterface $owner, string $type) : ?Wallet
    {
        return static::$modelClass::query()
                                          ->onlyOwner($owner)
                                          ->where('type', $type)
                                          ->first();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('status'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null) : array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }
}
