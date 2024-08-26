<?php

namespace RedJasmine\Order\Application\Services\Handlers;

use Illuminate\Support\Str;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Facades\ServiceContext;


abstract class AbstractOrderCommandHandler extends CommandHandler
{

    protected ?string $pipelinesConfigKeyPrefix = 'red-jasmine.order.pipelines';

    protected ?Order $aggregate = null;

    public function __construct(protected OrderRepositoryInterface $orderRepository)
    {
        parent::__construct();
    }


    protected function find(int $id) : Order
    {
        $order = $this->orderRepository->find($id);
        $this->setModel($order);

        $order->updater = ServiceContext::getOperator();
        return $order;

    }
}
