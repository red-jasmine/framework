<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories\Eloquent;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Wallet\Domain\Models\Wallet;
use RedJasmine\Wallet\Domain\Models\WalletWithdrawal;
use RedJasmine\Wallet\Domain\Repositories\WalletRepositoryInterface;
use RedJasmine\Wallet\Domain\Repositories\WalletWithdrawalRepositoryInterface;


class WalletWithdrawalRepository extends EloquentRepository implements WalletWithdrawalRepositoryInterface
{


    protected static string $eloquentModelClass = WalletWithdrawal::class;

    public function findByNo(string $no) : WalletWithdrawal
    {
        return static::$eloquentModelClass::where('withdrawal_no', $no)->firstOrFail();
    }


}