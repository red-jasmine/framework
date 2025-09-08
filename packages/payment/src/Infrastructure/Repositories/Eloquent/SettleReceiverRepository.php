<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class SettleReceiverRepository extends Repository implements SettleReceiverRepositoryInterface
{

    protected static string $modelClass = SettleReceiver::class;


}
