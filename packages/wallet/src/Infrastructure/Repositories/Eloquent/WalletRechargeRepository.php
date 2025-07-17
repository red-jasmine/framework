<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories\Eloquent;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeRepositoryInterface;


class WalletRechargeRepository extends EloquentRepository implements WalletRechargeRepositoryInterface
{


    protected static string $eloquentModelClass = WalletRecharge::class;

    public function findByNo(string $no)
    {
        return static::$eloquentModelClass::uniqueNo($no)->firstOrFail();
    }

    public function findByNoLock(string $no)
    {
        return static::$eloquentModelClass::lockForUpdate()->uniqueNo($no)->firstOrFail();
    }


}