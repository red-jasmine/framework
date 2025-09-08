<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories\Eloquent;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;


class WalletRepository extends Repository implements WalletRepositoryInterface
{


    protected static string $modelClass = Wallet::class;

    public function findLock($id) : Wallet
    {
        return static::$modelClass::query()->lockForUpdate()->findOrFail($id);
    }

    public function findByOwnerType(UserInterface $owner, string $type) : ?Wallet
    {
        return static::$modelClass::query()
                                  ->onlyOwner($owner)
                                  ->where('type', $type)
                                  ->first();
    }


}