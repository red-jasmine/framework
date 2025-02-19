<?php

namespace RedJasmine\Order\Application\Services\Handlers\Refund;

use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Domain\Models\OrderRefund;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;


abstract class AbstractRefundCommandHandler extends CommandHandler
{


    public function __construct(
        public RefundCommandService $service,
        protected RefundRepositoryInterface $refundRepository
    ) {

    }


    protected function find(int $id) : OrderRefund
    {
        return $this->refundRepository->find($id);
    }


}
