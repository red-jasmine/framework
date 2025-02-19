<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Application\Services\Orders\OrderCommandService;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;


abstract class AbstractOrderCommandHandler extends CommandHandler
{


    protected ?Order $aggregate = null;

    public function __construct(
        protected OrderCommandService $service
    ) {

    }


    public function findByNo(string $no) : Order
    {
        $order = $this->service->repository->findByNo($no);

        $this->setModel($order);
        return $order;
    }


    protected function find(int $id) : Order
    {
        $order = $this->service->repository->find($id);

        $this->setModel($order);
        return $order;

    }
}
