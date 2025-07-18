<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories\Eloquent;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;


class WalletWithdrawalRepository extends EloquentRepository implements WalletWithdrawalRepositoryInterface
{


    protected static string $eloquentModelClass = WalletWithdrawal::class;

    public function findByNo(string $no) : WalletWithdrawal
    {
        return static::$eloquentModelClass::where('no', $no)->firstOrFail();
    }


}