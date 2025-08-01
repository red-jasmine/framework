'<?php


use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderHiddenCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderMessageCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderProgressCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderRemarksCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderSellerCustomStatusCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderStarCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
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
    $this->orderCommandService = app(OrderApplicationService::class);

    $orderFake               = new OrderDummyFake();
    $orderFake->orderType    = OrderTypeEnum::STANDARD;
    $orderFake->shippingType = ShippingTypeEnum::DUMMY;
    $this->orderFake         = $orderFake;
    //
});

test('can create a new order', function () {


    $command = OrderCreateCommand::from($this->orderFake->order());

    $result = $this->orderCommandService->create($command);


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

    $order = $this->orderRepository->findByNo($order->order_no);

    $this->assertEquals(PaymentStatusEnum::PAID->value, $order->payment_status->value);
    $this->assertTrue($order->payable_amount->equals($order->payment_amount));
    return $result;

})->depends('can create a new order', 'cna paying a order');

// 设置进度
test('can progress a order', function (Order $order) {

    $commands = [];
    // 订单备注
    $message = '测试留言';
    // 订单商品项备注
    $orderNo        = $order->order_no;
    $progress       = 10;
    $orderProductNo = $order->products->first()->order_product_no;
    $command        = OrderProgressCommand::from([
        'orderNo'        => $order->order_no,
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
        'orderNo'        => $order->order_no,
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
        'orderNo'        => $order->order_no,
        'orderProductNo' => $orderProductNo,
        'progress'       => $progress,
        'isAppend'       => false,
        'isAllowLess'    => false,
    ]);


    $this->expectException(OrderException::class);
    // 设置进度
    $this->orderCommandService->progress($command);


    $command = OrderProgressCommand::from([
        'orderNo'        => $order->order_no,
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
        'order_no'        => $order->order_no,
        'order_products' => $order->products->pluck('order_product_no')->toArray()
    ]);


    $this->orderCommandService->dummyShipping($command);

    /**
     * @var $order Order
     */
    $order = $this->orderRepository->findByNo($order->order_no);

    $this->assertEquals($order->order_status, OrderStatusEnum::CONFIRMING, '订单状态');
    $this->assertEquals($order->shipping_status, ShippingStatusEnum::SHIPPED, '发货状态');


    return $order;
})->depends('can create a new order', 'cna paying a order', 'can paid a order');


test('can confirm a order', function (Order $order) {

    $command = OrderConfirmCommand::from(['orderNo' => $order->order_no]);

    $this->orderCommandService->confirm($command);

    $order = $this->orderRepository->findByNo($order->order_no);

    $this->assertEquals($order->order_status, OrderStatusEnum::FINISHED, '订单状态');


})->depends('can shipped a order');


test('can custom status a order', function (Order $order) {

    $sellerCustomStatus = 'TEST';
    $commands[]         = OrderSellerCustomStatusCommand::from([
        'orderNo'            => $order->order_no,
        'sellerCustomStatus' => $sellerCustomStatus

    ]);


    foreach ($order->products as $product) {
        $commands[] = OrderSellerCustomStatusCommand::from([
            'orderNo'            => $order->order_no, 'id' => $order->id,
            'orderProductId'     => $product->id,
            'sellerCustomStatus' => $sellerCustomStatus

        ]);
    }

    foreach ($commands as $command) {
        $this->orderCommandService->sellerCustomStatus($command);
    }


    $order = $this->orderRepository->findByNo($order->order_no);

    $this->assertEquals($order->seller_custom_status, $sellerCustomStatus, '自定义状态设置失败');

    foreach ($order->products as $product) {

        $this->assertEquals($product->seller_custom_status, $sellerCustomStatus, '自定义状态设置失败');
    }


})->depends('can shipped a order');


test('can star a order', function (Order $order) {

    $star    = 1;
    $command = OrderStarCommand::from([
        'orderNo' => $order->order_no,
        'star'    => $star
    ]);

    $this->orderCommandService->star($command);

    $order = $this->orderRepository->findByNo($order->order_no);

    $this->assertEquals($order->star, $star, ' 加星设置失败');


    $star    = null;
    $command = OrderStarCommand::from([
        'orderNo' => $order->order_no,
        'star'    => $star
    ]);

    $this->orderCommandService->star($command);

    $order = $this->orderRepository->findByNo($order->order_no);

    $this->assertEquals($order->star, $star, '加星设置失败');


})->depends('can shipped a order');


test('can remarks a order', function (Order $order) {

    $commands = [];
    // 订单备注
    $remarks = '测试备注';
    // 订单商品项备注
    $commands[] = OrderRemarksCommand::from([
        'orderNo' => $order->order_no,
        'remarks' => $remarks
    ]);


    foreach ($order->products as $product) {
        $commands[] = OrderRemarksCommand::from([
            'orderNo'        => $order->order_no,
            'orderProductId' => $product->id,
            'remarks'        => $remarks,
        ]);
    }

    foreach ($commands as $command) {
        $this->orderCommandService->sellerRemarks($command);
        $this->orderCommandService->buyerRemarks($command);
    }


    $command           = OrderRemarksCommand::from([
        'orderNo' => $order->order_no,
        'remarks' => $remarks
    ]);
    $command->isAppend = true;

    $this->orderCommandService->sellerRemarks($command);
    $this->orderCommandService->buyerRemarks($command);


    $order = $this->orderRepository->findByNo($order->order_no);

    $actualCount  = \Illuminate\Support\Str::substrCount($order->extension->seller_remarks, $remarks);
    $actualCount1 = \Illuminate\Support\Str::substrCount($order->extension->buyer_remarks, $remarks);
    $this->assertEquals($actualCount, 2);
    $this->assertEquals($actualCount1, 2);

    foreach ($order->products as $product) {
        $this->assertEquals($product->extension->seller_remarks, $remarks, '订单商品项目备注不匹配');
        $this->assertEquals($product->extension->buyer_remarks, $remarks, '订单商品项目备注不匹配');
    }

})->depends('can create a new order');


test('can message a order', function (Order $order) {

    $commands = [];
    // 订单备注
    $message = '测试留言';
    // 订单商品项备注
    $commands[] = OrderMessageCommand::from([
        'orderNo' => $order->order_no,
        'message' => $message
    ]);


    foreach ($order->products as $product) {
        $commands[] = OrderMessageCommand::from([
            'orderNo'        => $order->order_no,
            'orderProductId' => $product->id,
            'message'        => $message
        ]);
    }

    foreach ($commands as $command) {
        $this->orderCommandService->sellerMessage($command);
        $this->orderCommandService->buyerMessage($command);
    }


    $command           = OrderMessageCommand::from([
        'orderNo' => $order->order_no,
        'message' => $message
    ]);
    $command->isAppend = true;

    $this->orderCommandService->sellerMessage($command);
    $this->orderCommandService->buyerMessage($command);


    $order = $this->orderRepository->findByNo($order->order_no);

    $actualCount  = \Illuminate\Support\Str::substrCount($order->extension->seller_message, $message);
    $actualCount1 = \Illuminate\Support\Str::substrCount($order->extension->buyer_message, $message);
    $this->assertEquals($actualCount, 2);
    $this->assertEquals($actualCount1, 2);

    foreach ($order->products as $product) {
        $this->assertEquals($product->extension->seller_message, $message, '订单商品项目留言不匹配');
        $this->assertEquals($product->extension->buyer_message, $message, '订单商品项目留言不匹配');
    }

})->depends('can create a new order');


test('can hidden a order', function (Order $order) {


    $command = OrderHiddenCommand::from([
        'orderNo'  => $order->order_no,
        'isHidden' => true,

    ]);


    $this->orderCommandService->sellerHidden($command);
    $this->orderCommandService->buyerHidden($command);

    $order = $this->orderRepository->findByNo($order->order_no);
    $this->assertEquals($order->is_seller_delete, true, '卖家隐藏');
    $this->assertEquals($order->is_buyer_delete, true, '买家隐藏');


    // 设置为显示
    $command = OrderHiddenCommand::from([
        'orderNo'  => $order->order_no,
        'isHidden' => false,
    ]);

    $this->orderCommandService->sellerHidden($command);
    $this->orderCommandService->buyerHidden($command);


    $order = $this->orderRepository->findByNo($order->order_no);
    $this->assertEquals($order->is_seller_delete, false, '卖家显示');
    $this->assertEquals($order->is_buyer_delete, false, '买家显示');

})->depends('can create a new order');




