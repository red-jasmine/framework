<?php

namespace RedJasmine\Order\Application\Services\Handlers\Shipping;

use RedJasmine\Order\Application\Services\Handlers\AbstractOrderCommandHandler;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderDummyShippingCommand;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Services\OrderShippingService;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderDummyShippingCommandHandler extends AbstractOrderCommandHandler
{

    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderShippingService     $orderShippingService
    )
    {

        parent::__construct($orderRepository);
    }


    public function handle(OrderDummyShippingCommand $command) : void
    {


        $this->beginDatabaseTransaction();

        try {
            // 订单状态必须在发货中 或者
            // TODO 判断订单状态
            $order = $this->find($command->id);

            $order->products;

            if (count($command->orderProducts) <= 0) {
                $command->orderProducts = $order->products->pluck('id')->toArray();
            }

            foreach ($command->orderProducts as $orderProductId) {
                $this->orderShippingService->dummy($order, $orderProductId, $command->isFinished);
            }

            $this->orderRepository->update($order);

            $this->commitDatabaseTransaction();
        } catch (AbstractException $exception) {
            $this->rollBackDatabaseTransaction();
            throw  $exception;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw  $throwable;
        }

    }


}
