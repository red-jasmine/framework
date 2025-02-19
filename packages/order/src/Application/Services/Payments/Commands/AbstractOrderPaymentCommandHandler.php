<?php

namespace RedJasmine\Order\Application\Services\Payments\Commands;

use RedJasmine\Order\Domain\Repositories\OrderPaymentRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;

abstract class AbstractOrderPaymentCommandHandler extends CommandHandler
{
    public function __construct(protected OrderPaymentRepositoryInterface $orderPaymentRepository)
    {

    }



}
