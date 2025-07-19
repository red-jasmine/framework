<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Application\HandleContext;


abstract class AbstractOrderCommandHandler extends CommandHandler
{

    protected HandleContext $context;


    public function __construct(
        protected OrderApplicationService $service
    ) {

        $this->context = new HandleContext();
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
