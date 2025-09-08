<?php

namespace RedJasmine\Wallet\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;

/**
 * 钱包提现仓库接口
 *
 * 提供钱包提现实体的读写操作统一接口
 */
interface WalletWithdrawalRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据编号查找提现记录
     */
    public function findByNo(string $no) : WalletWithdrawal;

    /**
     * 根据编号查找并锁定提现记录
     */
    public function findByNoLock(string $no) : WalletWithdrawal;
}
