<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use RedJasmine\Order\Domain\Models\OrderRefund;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Facades\ServiceContext;

abstract class AbstractRefundCommandHandler extends CommandHandler
{


    protected ?OrderRefund $aggregate = null;

    public function __construct(protected RefundRepositoryInterface $refundRepository)
    {

    }


    protected function find(int $id) : OrderRefund
    {
        $refund = $this->refundRepository->find($id);
        $this->setAggregate($refund);
        $refund->updater = ServiceContext::getOperator();
        return $refund;
    }


}
