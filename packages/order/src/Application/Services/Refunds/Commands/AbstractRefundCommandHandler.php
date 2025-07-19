<?php

namespace RedJasmine\Order\Application\Services\Refunds\Commands;

use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;
use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Support\Application\Commands\CommandHandler;


abstract class AbstractRefundCommandHandler extends CommandHandler
{


    public function __construct(
        public RefundApplicationService $service,
        protected RefundRepositoryInterface $refundRepository
    ) {

    }


    protected function find(int $id) : Refund
    {
        return $this->service->repository->find($id);
    }

    protected function findByNo(string $no) : Refund
    {
        return $this->service->repository->findByNo($no);
    }


}
