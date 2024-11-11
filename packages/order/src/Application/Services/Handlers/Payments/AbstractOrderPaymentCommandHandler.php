<?php

namespace RedJasmine\Order\Application\Services\Handlers\Payments;

use RedJasmine\Order\Application\UserCases\Commands\Payments\OrderPaymentPaidCommand;
use RedJasmine\Order\Domain\Repositories\OrderPaymentRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

abstract class AbstractOrderPaymentCommandHandler extends CommandHandler
{
    public function __construct(protected OrderPaymentRepositoryInterface $orderPaymentRepository)
    {

    }



}
