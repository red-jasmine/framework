'<?php


use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderAcceptCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Tests\Feature\Order\Fixtures\OrderDummyFake;


beforeEach(function () {

    $this->orderRepository     = app(OrderRepositoryInterface::class);
    $this->orderCommandService = app(OrderApplicationService::class);

    $orderFake                 = new OrderDummyFake();
    $orderFake->orderType      = OrderTypeEnum::STANDARD;
    $orderFake->shippingType   = ShippingTypeEnum::DUMMY;
    $orderFake->accept_timeout = 30;
    $this->orderFake           = $orderFake;
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
            'orderNo' => $order->order_no,
            'amount'  => $order->payable_amount

        ]

    );


    $result = $this->orderCommandService->paying($command);

    //Event::assertDispatched(OrderPayingEvent::class, null);

    $this->assertInstanceOf(OrderPayment::class, $result, '创建支付记录失败');


    return $result;

})->depends('can create a new order');


test('can paid a order', function (Order $order, OrderPayment $orderPayment) {


    $command = new  OrderPaidCommand;

    $command->orderNo          = $order->order_no;
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
    $this->assertEquals(OrderStatusEnum::ACCEPTING->value, $order->order_status->value);
    $this->assertEquals(PaymentStatusEnum::PAID->value, $order->payment_status->value);
    $this->assertEquals($order->payable_amount->getAmount(), $order->payment_amount->getAmount());
    return $result;

})->depends('can create a new order', 'cna paying a order');


test('can accept a order', function (Order $order, OrderPayment $orderPayment) {


    $command = new  OrderAcceptCommand();

    $command->orderNo = $order->order_no;

    $result = $this->orderCommandService->accept($command);

    $this->assertTrue($result);

    $order = $this->orderRepository->findByNo($order->order_no);

    $this->assertEquals(OrderStatusEnum::SHIPPING->value, $order->order_status->value);

    return $result;

})->depends('can create a new order', 'cna paying a order', 'can paid a order');

// 设置进度
test('can progress a order', function (Order $order) {

    $commands = [];
    // 订单备注

    // 订单商品项备注
    $orderNo        = $order->order_no;
    $progress       = 10;
    $orderProductNo = $order->products->first()->order_product_no;
    $command        = OrderProgressCommand::from([
        'orderNo'        => $orderNo,
        'orderProductNo' => $orderProductNo,
        'progress'       => $progress,
        'isAppend'       => false,
        'isAllowLess'    => false,
    ]);


    // 设置进度
    $this->orderCommandService->progress($command);
    $order        = $this->orderRepository->findByNo($orderNo);
    $orderProduct = $order->products->where('order_product_no', $orderProductNo)->firstOrFail();

    $this->assertEquals($orderProduct->progress, $progress, '进度设置失败');

    $command = OrderProgressCommand::from([
        'orderNo'        => $orderNo,
        'orderProductNo' => $orderProductNo,
        'progress'       => $progress,
        'isAppend'       => true,
        'isAllowLess'    => false,
    ]);


    $this->orderCommandService->progress($command);
    $order        = $this->orderRepository->findByNo($orderNo);
    $orderProduct = $order->products->where('order_product_no', $orderProductNo)->firstOrFail();

    $this->assertEquals($orderProduct->progress, $progress + $progress, '进度设置失败');


    $command = OrderProgressCommand::from([
        'orderNo'             => $orderNo,
        'orderProductNo' => $orderProductNo,
        'progress'       => $progress,
        'isAppend'       => false,
        'isAllowLess'    => false,
    ]);


    $this->expectException(OrderException::class);
    // 设置进度
    $this->orderCommandService->progress($command);


    $command = OrderProgressCommand::from([
        'orderNo'        => $orderNo,
        'orderProductNo' => $orderProductNo,
        'progress'       => $progress,
        'isAppend'       => false,
        'isAllowLess'    => true,
    ]);


    $this->expectException(OrderException::class);
    // 设置进度
    $this->orderCommandService->progress($command);


    $order        = $this->orderRepository->findByNo($orderNo);
    $orderProduct = $order->products->where('order_product_no', $orderProductNo)->firstOrFail();

    $this->assertEquals($orderProduct->progress, $progress, '进度设置失败');


})->depends('can create a new order');


test('can shipped a order', function (Order $order, OrderPayment $orderPayment, $result) {


    $command = $this->orderFake->shippingDummy([
        'order_no'             => $order->order_no,
        'order_products' => $order->products->pluck('order_product_no')->toArray()
    ]);


    $this->orderCommandService->dummyShipping($command);

    /**
     * @var $order Order
     */
    $order = $this->orderRepository->find($order->id);

    $this->assertEquals($order->order_status, OrderStatusEnum::CONFIRMING, '订单状态');
    $this->assertEquals($order->shipping_status, ShippingStatusEnum::SHIPPED, '发货状态');


    return $order;
})->depends('can create a new order', 'cna paying a order', 'can paid a order');


test('can confirm a order', function (Order $order) {

    $command = OrderConfirmCommand::from(['orderNo' => $order->order_no]);

    $this->orderCommandService->confirm($command);

    $order = $this->orderRepository->find($order->id);

    $this->assertEquals($order->order_status, OrderStatusEnum::FINISHED, '订单状态');


})->depends('can shipped a order');



