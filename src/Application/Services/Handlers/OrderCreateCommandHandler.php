<?php

namespace RedJasmine\Order\Application\Services\Handlers;


use Exception;
use RedJasmine\Order\Application\Mappers\OrderAddressMapper;
use RedJasmine\Order\Application\Mappers\OrderMapper;
use RedJasmine\Order\Application\Mappers\OrderProductMapper;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Events\OrderCreatedEvent;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\OrderFactory;
use RedJasmine\Support\Facades\ServiceContext;

class OrderCreateCommandHandler extends AbstractOrderCommandHandler
{

    /**
     * @param OrderCreateCommand $data
     *
     * @return Order
     * @throws Exception
     */
    public function handle(OrderCreateCommand $data) : Order
    {
        $order = app(OrderFactory::class)->createOrder();


        $this->setModel($order);

        // TODO 这里割裂了应该在当前类设置
        app(OrderMapper::class)->fromData($data, $order);

        foreach ($data->products as $productData) {
            $product = app(OrderFactory::class)->createOrderProduct();
            // 这里也是
            app(OrderProductMapper::class)->fromData($productData, $product);
            // TODO creator 没有存储进来
            $order->addProduct($product);
        }

        // TODO 判断是否需要 地址
        if ($data->address) {
            $address = app(OrderFactory::class)->createOrderAddress();
            app(OrderAddressMapper::class)->fromData($data->address, $address);
            $order->setAddress($address);
        }

        $order->creator = ServiceContext::getOperator();

        $this->execute(
            execute: fn() => $order->create(),
            persistence: fn() => $this->orderRepository->store($order)
        );

        OrderCreatedEvent::dispatch($order);
        return $order;

    }


}
