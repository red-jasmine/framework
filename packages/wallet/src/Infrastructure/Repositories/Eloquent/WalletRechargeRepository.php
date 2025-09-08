<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories\Eloquent;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeRepositoryInterface;


class WalletRechargeRepository extends Repository implements WalletRechargeRepositoryInterface
{


    protected static string $modelClass = WalletRecharge::class;

    public function findByNo(string $no)
    {
        return static::$modelClass::uniqueNo($no)->firstOrFail();
    }

    public function findByNoLock(string $no)
    {
        return static::$modelClass::lockForUpdate()->uniqueNo($no)->firstOrFail();
    }


}