<?php

namespace RedJasmine\Wallet\Domain\Repositories;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Wallet\Domain\Models\Wallet;

/**
 * 钱包仓库接口
 *
 * 提供钱包实体的读写操作统一接口
 */
interface WalletRepositoryInterface extends RepositoryInterface
{
    /**
     * 查找并锁定钱包
     */
    public function findLock($id) : Wallet;

    /**
     * 根据所有者和类型查找钱包
     */
    public function findByOwnerType(UserInterface $owner, string $type) : ?Wallet;
}
