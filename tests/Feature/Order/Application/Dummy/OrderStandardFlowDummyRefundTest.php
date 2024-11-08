'<?php


use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeRefundCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCreateCommand;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\RefundReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Tests\Feature\Order\Fixtures\OrderDummyFake;


beforeEach(function () {

    $this->orderReadRepository  = app(OrderReadRepositoryInterface::class);
    $this->orderRepository      = app(OrderRepositoryInterface::class);
    $this->orderCommandService  = app(OrderCommandService::class);
    $this->refundCommandService = app(RefundCommandService::class);
    $this->refundRepository     = app(RefundRepositoryInterface::class);
    $this->refundReadRepository = app(RefundReadRepositoryInterface::class);

    $orderFake               = new OrderDummyFake();
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


// 退款


test('can refund a order', function (Order $order, OrderPayment $orderPayment) {

    $commands    = [];
    $command     = new RefundCreateCommand;
    $command->id = $order->id;

    $refunds = [];
    foreach ($order->products as $product) {
        $command                 = new RefundCreateCommand;
        $command->id             = $order->id;
        $command->orderProductId = $product->id;
        $command->refundType     = RefundTypeEnum::REFUND;
        $command->refundAmount   = $product->payment_amount;
        $command->reason         = '不想要了';
        $command->description    = fake()->sentence;
        $command->outerRefundId  = fake()->numerify('######');
        $command->images         = [ fake()->imageUrl, fake()->imageUrl, fake()->imageUrl, ];

        $commands [] = $command;


        $refunds[] = $this->refundCommandService->create($command);
    }


    $order = $this->orderRepository->find($order->id);

    foreach ($order->products as $product) {

        $this->assertEquals(RefundStatusEnum::WAIT_SELLER_AGREE->value, $product->refund_status->value, '退款状态不正确');
    }

    return $refunds;

})
    ->depends('can create a new order', 'cna paying a order', 'can paid a order');


test('can agree refund a order', function (Order $order, $refunds = []) {


    foreach ($refunds as $refundId) {

        $refund          = $this->refundRepository->find($refundId);
        $command         = new RefundAgreeRefundCommand();
        $command->rid    = $refund->id;
        $command->amount = $refund->refund_amount;

        $this->refundCommandService->agreeRefund($command);
    }


    $order = $this->orderRepository->find($order->id);


    // 订单为无效单  已关闭
    // TODO 检查退款金额

    $this->assertEquals(OrderStatusEnum::CLOSED, $order->order_status, ' 订单状态不正确');


    foreach ($order->products as $product) {
        $this->assertEquals(RefundStatusEnum::FINISHED->value, $product->refund_status->value, '退款状态不正确');
        $this->assertEquals($product->divided_payment_amount->value(), $product->refund_amount->value(), '退款金额不正确');
    }

    return $order;

})->depends('can create a new order', 'can refund a order');






