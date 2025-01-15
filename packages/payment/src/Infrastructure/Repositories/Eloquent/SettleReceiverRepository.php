<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Repositories\SettleReceiverRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class SettleReceiverRepository extends EloquentRepository implements SettleReceiverRepositoryInterface
{

    protected static string $eloquentModelClass = SettleReceiver::class;


}
