<?php

namespace RedJasmine\Order\Infrastructure\Repositories\Eloquent;


use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderPaymentRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class OrderPaymentRepository extends Repository implements OrderPaymentRepositoryInterface
{

    protected static string $modelClass = OrderPayment::class;


}
