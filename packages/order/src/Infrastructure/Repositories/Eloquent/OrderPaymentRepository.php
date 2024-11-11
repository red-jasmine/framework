<?php

namespace RedJasmine\Order\Infrastructure\Repositories\Eloquent;


use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderPaymentRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class OrderPaymentRepository extends EloquentRepository implements OrderPaymentRepositoryInterface
{

    protected static string $eloquentModelClass = OrderPayment::class;


}
