<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 钱包充值仓库实现
 *
 * 基于Repository实现，提供钱包充值实体的读写操作能力
 */
class WalletRechargeRepository extends Repository implements WalletRechargeRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = WalletRecharge::class;



    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('wallet_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
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
