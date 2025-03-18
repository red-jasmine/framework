<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Order\Application\Services\Refunds\RefundCommandService;
use RedJasmine\Order\Domain\Models\OrderRefund;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;


abstract class AbstractRefundCommandHandler extends CommandHandler
{


    public function __construct(
        public RefundCommandService $service,
        protected RefundRepositoryInterface $refundRepository
    ) {

    }


    protected function find(int $id) : OrderRefund
    {
        return $this->service->repository->find($id);
    }


}
