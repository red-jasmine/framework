<?php

namespace RedJasmine\Wallet\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 钱包充值仓库接口
 *
 * 提供钱包充值实体的读写操作统一接口
 */
interface WalletRechargeRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据编号查找充值记录
     */
    public function findByNo(string $no);

    /**
     * 根据编号查找并锁定充值记录
     */
    public function findByNoLock(string $no);
}
