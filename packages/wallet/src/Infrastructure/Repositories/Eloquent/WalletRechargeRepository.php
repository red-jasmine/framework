<?php

namespace RedJasmine\Wallet\Infrastructure\Repositories\Eloquent;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;
use RedJasmine\Wallet\Domain\Repositories\WalletRechargeRepositoryInterface;


class WalletRechargeRepository extends EloquentRepository implements WalletRechargeRepositoryInterface
{


    protected static string $eloquentModelClass = WalletRecharge::class;


}