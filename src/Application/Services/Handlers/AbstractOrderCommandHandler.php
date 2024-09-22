<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Support\Application\CommandHandlers\CommandHandler;


abstract class AbstractOrderCommandHandler extends CommandHandler
{


    protected ?Order $aggregate = null;

    public function __construct(protected OrderRepositoryInterface $orderRepository)
    {

    }


    protected function find(int $id) : Order
    {
        $order = $this->orderRepository->find($id);

        $this->setModel($order);
        return $order;

    }
}
