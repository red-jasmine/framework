<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;
use RedJasmine\Wallet\Domain\Repositories\WalletTransactionRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 钱包交易仓库实现
 *
 * 基于Repository实现，提供钱包交易实体的读写操作能力
 */
class WalletTransactionRepository extends Repository implements WalletTransactionRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = WalletTransaction::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('wallet_id'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('source_type'),
            AllowedFilter::exact('source_id'),
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
