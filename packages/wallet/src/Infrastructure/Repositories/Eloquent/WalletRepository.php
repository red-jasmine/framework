<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories\Eloquent;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;


class WalletRepository extends EloquentRepository implements WalletRepositoryInterface
{


    protected static string $eloquentModelClass = Wallet::class;

    public function findLock($id) : Wallet
    {
        return static::$eloquentModelClass::query()->lockForUpdate()->findOrFail($id);
    }

    public function findByOwnerType(UserInterface $owner, string $type) : ?Wallet
    {
        return static::$eloquentModelClass::query()
                                          ->onlyOwner($owner)
                                          ->where('type', $type)
                                          ->first();
    }


}