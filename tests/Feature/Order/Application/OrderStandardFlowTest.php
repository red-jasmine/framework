'<?php


use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\UserCases\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderHiddenCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderMessageCommand;
use RedJasmine\Order\Application\UserCases\Commands\Others\OrderRemarksCommand;
use RedJasmine\Order\Domain\Exceptions\OrderException;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Tests\Feature\Order\Fixtures\OrderFake;


beforeEach(function () {

    $this->orderReadRepository = app(OrderReadRepositoryInterface::class);
    $this->orderRepository     = app(OrderRepositoryInterface::class);
    $this->orderCommandService = app(OrderCommandService::class);

    $orderFake               = new OrderFake();
    $orderFake->orderType    = OrderTypeEnum::STANDARD;
    $orderFake->shippingType = ShippingTypeEnum::DUMMY;
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

    $this->assertEquals(PaymentStatusEnum::PAID->value, $order->payment_status->value);
    $this->assertEquals($order->payable_amount->value(), $order->payment_amount->value());
    return $result;

})->depends('can create a new order', 'cna paying a order');

// 设置进度
test('can progress a order',function (Order $order) {

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

    $this->assertEquals($orderProduct->progress, $progress , '进度设置失败');


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


test('can remarks a order', function (Order $order) {

    $commands = [];
    // 订单备注
    $remarks = '测试备注';
    // 订单商品项备注
    $commands[] = OrderRemarksCommand::from([
                                                'id'      => $order->id,
                                                'remarks' => $remarks
                                            ]);


    foreach ($order->products as $product) {
        $commands[] = OrderRemarksCommand::from([
                                                    'id'             => $order->id,
                                                    'orderProductId' => $product->id,
                                                    'remarks'        => $remarks,
                                                ]);
    }

    foreach ($commands as $command) {
        $this->orderCommandService->sellerRemarks($command);
        $this->orderCommandService->buyerRemarks($command);
    }


    $command           = OrderRemarksCommand::from([
                                                       'id'      => $order->id,
                                                       'remarks' => $remarks
                                                   ]);
    $command->isAppend = true;

    $this->orderCommandService->sellerRemarks($command);
    $this->orderCommandService->buyerRemarks($command);


    $order = $this->orderRepository->find($order->id);

    $actualCount  = \Illuminate\Support\Str::substrCount($order->info->seller_remarks, $remarks);
    $actualCount1 = \Illuminate\Support\Str::substrCount($order->info->buyer_remarks, $remarks);
    $this->assertEquals($actualCount, 2);
    $this->assertEquals($actualCount1, 2);

    foreach ($order->products as $product) {
        $this->assertEquals($product->info->seller_remarks, $remarks, '订单商品项目备注不匹配');
        $this->assertEquals($product->info->buyer_remarks, $remarks, '订单商品项目备注不匹配');
    }

})->depends('can create a new order');


test('can message a order', function (Order $order) {

    $commands = [];
    // 订单备注
    $message = '测试留言';
    // 订单商品项备注
    $commands[] = OrderMessageCommand::from([
                                                'id'      => $order->id,
                                                'message' => $message
                                            ]);


    foreach ($order->products as $product) {
        $commands[] = OrderMessageCommand::from([
                                                    'id'             => $order->id,
                                                    'orderProductId' => $product->id,
                                                    'message'        => $message
                                                ]);
    }

    foreach ($commands as $command) {
        $this->orderCommandService->sellerMessage($command);
        $this->orderCommandService->buyerMessage($command);
    }


    $command           = OrderMessageCommand::from([
                                                       'id'      => $order->id,
                                                       'message' => $message
                                                   ]);
    $command->isAppend = true;

    $this->orderCommandService->sellerMessage($command);
    $this->orderCommandService->buyerMessage($command);


    $order = $this->orderRepository->find($order->id);

    $actualCount  = \Illuminate\Support\Str::substrCount($order->info->seller_message, $message);
    $actualCount1 = \Illuminate\Support\Str::substrCount($order->info->buyer_message, $message);
    $this->assertEquals($actualCount, 2);
    $this->assertEquals($actualCount1, 2);

    foreach ($order->products as $product) {
        $this->assertEquals($product->info->seller_message, $message, '订单商品项目留言不匹配');
        $this->assertEquals($product->info->buyer_message, $message, '订单商品项目留言不匹配');
    }

})->depends('can create a new order');


test('can hidden a order', function (Order $order) {


    $command = OrderHiddenCommand::from([
                                            'id'       => $order->id,
                                            'isHidden' => true,

                                        ]);


    $this->orderCommandService->sellerHidden($command);
    $this->orderCommandService->buyerHidden($command);

    $order = $this->orderRepository->find($order->id);
    $this->assertEquals($order->is_seller_delete, true, '卖家隐藏');
    $this->assertEquals($order->is_buyer_delete, true, '买家隐藏');


    // 设置为显示
    $command = OrderHiddenCommand::from([
                                            'id'       => $order->id,
                                            'isHidden' => false,
                                        ]);

    $this->orderCommandService->sellerHidden($command);
    $this->orderCommandService->buyerHidden($command);


    $order = $this->orderRepository->find($order->id);
    $this->assertEquals($order->is_seller_delete, false, '卖家显示');
    $this->assertEquals($order->is_buyer_delete, false, '买家显示');

})->depends('can create a new order');




