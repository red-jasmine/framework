<?php

namespace RedJasmine\Order\Application\Services\Orders\Commands;

use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Services\OrderShippingService;
use RedJasmine\Support\Exceptions\AbstractException;
use Throwable;

class OrderDummyShippingCommandHandler extends AbstractOrderCommandHandler
{

    public function __construct(
        OrderApplicationService $service,
        OrderRepositoryInterface $orderRepository,
        protected OrderShippingService     $orderShippingService
    )
    {
        parent::__construct($service, $orderRepository);
    }



    public function handle(OrderDummyShippingCommand $command) : void
    {


        $this->beginDatabaseTransaction();

        try {
            // 订单状态必须在发货中 或者
            // TODO 判断订单状态
            $order = $this->findByNo($command->orderNo);

            $order->products;

            if (count($command->orderProducts) <= 0) {
                // TODO 排除 无效商品
                $command->orderProducts = $order->products->pluck('order_product_no')->toArray();
            }

            foreach ($command->orderProducts as $orderProductNo) {
                $this->orderShippingService->dummy($order, $orderProductNo, $command->isFinished);
            }

            $this->service->repository->update($order);

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
