'<?php


use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\UserCases\Commands\OrderAcceptCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderHiddenCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderMessageCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderSellerCustomStatusCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderStarCommand;
use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Tests\Feature\Order\Fixtures\OrderDummyFake;


beforeEach(function () {

    $this->orderReadRepository = app(OrderReadRepositoryInterface::class);
    $this->orderRepository     = app(OrderRepositoryInterface::class);
    $this->orderCommandService = app(OrderCommandService::class);

    $orderFake                       = new OrderDummyFake();
    $orderFake->orderType            = OrderTypeEnum::STANDARD;
    $orderFake->shippingType         = ShippingTypeEnum::DUMMY;
    $orderFake->accept_wait_max_time = 30;
    $this->orderFake                 = $orderFake;
    //
});

test('can create a new order', function () {


    $command = OrderCreateCommand::from($this->orderFake->order());
    $result  = $this->orderCommandService->create($command);

    $this->assertInstanceOf(Order::class, $result, '创建订单失败');
    $this->order = $result;

    return $result;
});


test('cna paying a order', function (Order $order) {


    //Event::fake();

    $command = OrderPayingCommand::from(
        [
            'id'     => $order->id,
            'amount' => $order->payable_amount

        ]

    );


    $result = $this->orderCommandService->paying($command);

    //Event::assertDispatched(OrderPayingEvent::class, null);

    $this->assertInstanceOf(OrderPayment::class, $result, '创建支付记录失败');


    return $result;

})->depends('can create a new order');


test('can paid a order', function (Order $order, OrderPayment $orderPayment) {


    $command = new  OrderPaidCommand;

    $command->id               = $order->id;
    $command->orderPaymentId   = $orderPayment->id;
    $command->amount           = $orderPayment->payment_amount;
    $command->paymentType      = 'online';
    $command->paymentId        = fake()->numberBetween(1000000, 999999999);
    $command->paymentChannel   = 'alipay';
    $command->paymentChannelNo = fake()->numerify('channel-no-########');
    $command->paymentTime      = date('Y-m-d H:i:s');


    $result = $this->orderCommandService->paid($command);


    $this->assertTrue($result);

    $order = $this->orderRepository->find($order->id);
    $this->assertEquals(OrderStatusEnum::WAIT_SELLER_ACCEPT->value, $order->order_status->value);
    $this->assertEquals(PaymentStatusEnum::PAID->value, $order->payment_status->value);
    $this->assertEquals($order->payable_amount->value(), $order->payment_amount->value());
    return $result;

})->depends('can create a new order', 'cna paying a order');


test('can accept a order', function (Order $order, OrderPayment $orderPayment) {


    $command = new  OrderAcceptCommand();

    $command->id = $order->id;

    $result = $this->orderCommandService->accept($command);

    $this->assertTrue($result);

    $order = $this->orderRepository->find($order->id);

    $this->assertEquals(OrderStatusEnum::WAIT_SELLER_SEND_GOODS->value, $order->order_status->value);

    return $result;

})->depends('can create a new order', 'cna paying a order', 'can paid a order');

// 设置进度
test('can progress a order', function (Order $order) {

    $commands = [];
    // 订单备注
    $message = '测试留言';
    // 订单商品项备注
    $orderId        = $order->id;
    $progress       = 10;
    $orderProductId = $order->products->first()->id;
    $command        = OrderProgressCommand::from([
                                                     'id'             => $orderId,
                                                     'orderProductId' => $orderProductId,
                                                     'progress'       => $progress,
                                                     'isAppend'       => false,
                                                     'isAllowLess'    => false,
                                                 ]);


    // 设置进度
    $this->orderCommandService->progress($command);
    $order        = $this->orderRepository->find($orderId);
    $orderProduct = $order->products->where('id', $orderProductId)->firstOrFail();

    $this->assertEquals($orderProduct->progress, $progress, '进度设置失败');

    $command = OrderProgressCommand::from([
                                              'id'             => $orderId,
                                              'orderProductId' => $orderProductId,
                                              'progress'       => $progress,
                                              'isAppend'       => true,
                                              'isAllowLess'    => false,
                                          ]);


    $this->orderCommandService->progress($command);
    $order        = $this->orderRepository->find($orderId);
    $orderProduct = $order->products->where('id', $orderProductId)->firstOrFail();

    $this->assertEquals($orderProduct->progress, $progress + $progress, '进度设置失败');


    $command = OrderProgressCommand::from([
                                              'id'             => $orderId,
                                              'orderProductId' => $orderProductId,
                                              'progress'       => $progress,
                                              'isAppend'       => false,
                                              'isAllowLess'    => false,
                                          ]);


    $this->expectException(OrderException::class);
    // 设置进度
    $this->orderCommandService->progress($command);


    $command = OrderProgressCommand::from([
                                              'id'             => $orderId,
                                              'orderProductId' => $orderProductId,
                                              'progress'       => $progress,
                                              'isAppend'       => false,
                                              'isAllowLess'    => true,
                                          ]);


    $this->expectException(OrderException::class);
    // 设置进度
    $this->orderCommandService->progress($command);


    $order        = $this->orderRepository->find($orderId);
    $orderProduct = $order->products->where('id', $orderProductId)->firstOrFail();

    $this->assertEquals($orderProduct->progress, $progress, '进度设置失败');


})->depends('can create a new order');


test('can shipped a order', function (Order $order, OrderPayment $orderPayment, $result) {


    $command = $this->orderFake->shippingDummy([
                                                   'id'             => $order->id,
                                                   'order_products' => $order->products->pluck('id')->toArray()
                                               ]);

    $this->orderCommandService->dummyShipping($command);

    /**
     * @var $order Order
     */
    $order = $this->orderRepository->find($order->id);

    $this->assertEquals($order->order_status, OrderStatusEnum::WAIT_BUYER_CONFIRM_GOODS, '订单状态');
    $this->assertEquals($order->shipping_status, ShippingStatusEnum::SHIPPED, '发货状态');


    return $order;
})->depends('can create a new order', 'cna paying a order', 'can paid a order');


test('can confirm a order', function (Order $order) {

    $command = OrderConfirmCommand::from([ 'id' => $order->id ]);

    $this->orderCommandService->confirm($command);

    $order = $this->orderRepository->find($order->id);

    $this->assertEquals($order->order_status, OrderStatusEnum::FINISHED, '订单状态');


})->depends('can shipped a order');



