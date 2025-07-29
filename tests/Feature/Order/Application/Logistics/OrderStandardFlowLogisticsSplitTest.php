'<?php


use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\Logistics\Commands\LogisticsChangeStatusCommand;
use RedJasmine\Order\Application\Services\Logistics\OrderLogisticsApplicationService;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsStatusEnum;
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

    $this->orderReadRepository          = app(OrderReadRepositoryInterface::class);
    $this->orderRepository              = app(OrderRepositoryInterface::class);
    $this->orderCommandService          = app(OrderApplicationService::class);
    $this->orderLogisticsCommandService = app(OrderLogisticsApplicationService::class);

    $orderFake               = new OrderDummyFake();
    $orderFake->orderType    = OrderTypeEnum::STANDARD;
    $orderFake->shippingType = ShippingTypeEnum::LOGISTICS;
    $this->orderFake         = $orderFake;
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

    $this->assertEquals(PaymentStatusEnum::PAID->value, $order->payment_status->value);
    $this->assertEquals($order->payable_amount->getAmount(), $order->payment_amount->getAmount());
    return $result;

})->depends('can create a new order', 'cna paying a order');


test('can shipped a order', function (Order $order, OrderPayment $orderPayment, $result) {

    $logisticsCommands = [];
    // 拆分发货
    // 对 商品1 多次分开发货
    $num      = 2;
    $product1 = $order->products[0];
    for ($i = 1; $i <= $num; $i++) {
        $isFinished = false;
        if ($i === $num) {

            $isFinished = true;
        }
        $command             = $this->orderFake->shippingLogistics([
            'order_no'       => $order->order_no,
            'is_split'       => true,
            'is_finished'    => $isFinished,
            'order_products' => [$product1->order_product_no]
        ]);
        $logisticsCommands[] = $command;
        $this->orderCommandService->logisticsShipping($command);

        /**
         * @var $order Order
         */
        $order    = $this->orderRepository->find($order->id);
        $product1 = $order->products[0];

        if ($i === $num) {

            $this->assertEquals($product1->order_status, OrderStatusEnum::CONFIRMING, '订单状态');
            $this->assertEquals($product1->shipping_status, ShippingStatusEnum::SHIPPED, '发货状态');

        } else {
            $this->assertEquals($product1->order_status, OrderStatusEnum::SHIPPING, '订单状态');
            $this->assertEquals($product1->shipping_status, ShippingStatusEnum::PART_SHIPPED, '发货状态');

        }
    }
    /**
     * @var $order Order
     */
    $order = $this->orderRepository->find($order->id);
    $this->assertEquals($order->order_status, OrderStatusEnum::SHIPPING, '订单状态');
    $this->assertEquals($order->shipping_status, ShippingStatusEnum::PART_SHIPPED, '发货状态');


    foreach ($order->products as $index => $product) {
        if ($index === 0) {
            continue;
        }
        $command             = $this->orderFake->shippingLogistics([
            'order_no'       => $order->order_no,
            'is_split'       => true,
            'is_finished'    => true,
            'order_products' => [$product->order_product_no]
        ]);
        $logisticsCommands[] = $command;
        $this->orderCommandService->logisticsShipping($command);
    }

    /**
     * @var $order Order
     */
    $order = $this->orderRepository->find($order->id);
    $this->assertEquals($order->order_status, OrderStatusEnum::CONFIRMING, '订单状态');
    $this->assertEquals($order->shipping_status, ShippingStatusEnum::SHIPPED, '发货状态');


    return $logisticsCommands;
})
    ->depends('can create a new order', 'cna paying a order', 'can paid a order');


test('can change logistics status', function (Order $order, OrderPayment $orderPayment, $logisticsCommands) {

    // 设置 物流状态

    foreach ($logisticsCommands as $command) {
        $changeStatusCommand = new   LogisticsChangeStatusCommand;

        $changeStatusCommand->logisticsCompanyCode = $command->logisticsCompanyCode;
        $changeStatusCommand->logisticsNo          = $command->logisticsNo;
        $changeStatusCommand->status               = LogisticsStatusEnum::SIGNED;

        $this->orderLogisticsCommandService->changeStatus($changeStatusCommand);
    }

    $order = $this->orderRepository->find($order->id);

    foreach ($order->logistics as $logistic) {
        $this->assertEquals(LogisticsStatusEnum::SIGNED->value, $logistic->status->value, '物流状态');
    }


})->depends('can create a new order', 'cna paying a order', 'can shipped a order');

test('can confirm a order', function (Order $order, $logisticsCommands) {

    $command = OrderConfirmCommand::from(['orderNo' => $order->order_no]);

    $this->orderCommandService->confirm($command);

    $order = $this->orderRepository->find($order->id);

    $this->assertEquals($order->order_status, OrderStatusEnum::FINISHED, '订单状态');


})->depends('can create a new order', 'can shipped a order');



