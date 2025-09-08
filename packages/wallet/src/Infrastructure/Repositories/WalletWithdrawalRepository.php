<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 钱包提现仓库实现
 *
 * 基于Repository实现，提供钱包提现实体的读写操作能力
 */
class WalletWithdrawalRepository extends Repository implements WalletWithdrawalRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = WalletWithdrawal::class;

    /**
     * 根据编号查找提现记录
     */
    public function findByNo(string $no) : WalletWithdrawal
    {
        return static::$modelClass::uniqueNo($no)->firstOrFail();
    }

    /**
     * 根据编号查找并锁定提现记录
     */
    public function findByNoLock(string $no) : WalletWithdrawal
    {
        return static::$modelClass::lockForUpdate()->uniqueNo($no)->firstOrFail();
    }

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
