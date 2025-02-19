<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;


abstract class AbstractOrderCommandHandler extends CommandHandler
{


    protected ?Order $aggregate = null;

    public function __construct(
        protected OrderCommandService $service)
    {

    }


    protected function find(int $id) : Order
    {
        $order = $this->service->repository->find($id);

        $this->setModel($order);
        return $order;

    }
}
