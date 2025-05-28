'<?php


use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderUrgeCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Application\Services\Payments\Commands\OrderPaymentFailCommand;
use RedJasmine\Order\Application\Services\Payments\Commands\OrderPaymentPaidCommand;
use RedJasmine\Order\Application\Services\Payments\Commands\OrderPaymentPayingCommand;
use RedJasmine\Order\Application\Services\Payments\OrderPaymentApplicationService;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeRefundCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCreateCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundUrgeCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderPaymentRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\RefundReadRepositoryInterface;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Tests\Feature\Order\Fixtures\OrderDummyFake;


beforeEach(function () {

    $this->orderReadRepository        = app(OrderReadRepositoryInterface::class);
    $this->orderRepository            = app(OrderRepositoryInterface::class);
    $this->orderCommandService        = app(OrderApplicationService::class);
    $this->refundCommandService       = app(RefundApplicationService::class);
    $this->refundRepository           = app(RefundRepositoryInterface::class);
    $this->refundReadRepository       = app(RefundReadRepositoryInterface::class);
    $this->orderPaymentRepository     = app(OrderPaymentRepositoryInterface::class);
    $this->orderPaymentCommandService = app(OrderPaymentApplicationService::class);

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
            'order_no' => $order->order_no,
            'amount'   => $order->payable_amount

        ]

    );


    $result = $this->orderCommandService->paying($command);

    //Event::assertDispatched(OrderPayingEvent::class, null);

    $this->assertInstanceOf(OrderPayment::class, $result, '创建支付记录失败');


    return $result;

})->depends('can create a new order');


test('can paid a order', function (Order $order, OrderPayment $orderPayment) {


    $command = OrderPaidCommand::from([
        'orderNo'        => $order->order_no,
        'orderPaymentId' => $orderPayment->id,
        'amount'         => $orderPayment->payment_amount->toArray()

    ]);


    $this->orderFake->fakeOrderPayment($command);


    $result = $this->orderCommandService->paid($command);


    $this->assertTrue($result);

    $order = $this->orderRepository->find($order->id);


    $this->assertEquals(PaymentStatusEnum::PAID->value, $order->payment_status->value);
    $this->assertTrue($order->payable_amount->equals($order->payment_amount));
    return $result;

})->depends('can create a new order', 'cna paying a order');

test('can urge a order', function (Order $order, OrderPayment $orderPayment) {

    $command = OrderUrgeCommand::from([
        'order_no' => $order->order_no,
    ]);

    $result = $this->orderCommandService->urge($command);


    $order = $this->orderRepository->find($order->id);

    $this->assertEquals(1, $order->urge);

    return $result;

})->depends('can create a new order', 'cna paying a order', 'can paid a order');

// 退款


test('can refund a order', function (Order $order, OrderPayment $orderPayment) {

    $commands = [];


    $refunds = [];
    foreach ($order->products as $product) {

        $command = RefundCreateCommand::from([
            'orderNo'        => $order->order_no,
            'orderProductNo' => $product->order_product_no,
            'refundType'     => RefundTypeEnum::REFUND,
            'refundAmount'   => $product->payment_amount,
            'reason'         => '不想要了',
            'description'    => fake()->sentence,
            'outerRefundId'  => fake()->numerify('######'),
            'images'         => [fake()->imageUrl, fake()->imageUrl, fake()->imageUrl,],
        ]);

        $refunds[] = $this->refundCommandService->create($command);

    }



    foreach ($refunds as $refundNo) {
        $refund  = $this->refundRepository->findByNo($refundNo);
        $this->assertEquals(RefundStatusEnum::WAIT_SELLER_AGREE->value, $refund->refund_status->value, '退款状态不正确');
    }

    return $refunds;

})
    ->depends('can create a new order', 'cna paying a order', 'can paid a order');

return;
test('can urge a refund', function (Order $order, $refunds = []) {

    foreach ($refunds as $refundId) {


        $command     = new RefundUrgeCommand();
        $command->id = $refundId;

        $this->refundCommandService->urge($command);

        $refund = $this->refundRepository->find($refundId);

        $this->assertEquals(1, $refund->urge);
    }


    return $order;

})->depends('can create a new order', 'can refund a order');


test('can agree refund a order', function (Order $order, $refunds = []) {


    foreach ($refunds as $refundId) {

        $refund          = $this->refundRepository->find($refundId);
        $command         = new RefundAgreeRefundCommand();
        $command->id     = $refund->id;
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

    return $refunds;

})->depends('can create a new order', 'can refund a order');

test('can refund payment paying', function (Order $order, $refunds = []) {

    foreach ($refunds as $refundId) {
        // 根据退款单 查询支付单
        $refund = $this->refundRepository->find($refundId);

        $orderPayment         = $refund->payments->first();
        $command              = new OrderPaymentPayingCommand();
        $command->id          = $orderPayment->id;
        $command->amount      = $orderPayment->payment_amount;
        $command->paymentType = 'online';
        $command->paymentId   = fake()->numberBetween(1000000, 999999999);
        //$command->paymentChannel   = 'alipay';
        //$command->paymentChannelNo = fake()->numerify('channel-no-########');
        //$command->paymentTime      = date('Y-m-d H:i:s');

        $this->orderPaymentCommandService->paying($command);
    }

    foreach ($refunds as $refundId) {
        // 根据退款单 查询支付单
        $refund       = $this->refundRepository->find($refundId);
        $orderPayment = $refund->payments->first();


        $this->assertEquals(PaymentStatusEnum::PAYING->value, $orderPayment->status->value, '支付状态不正确');

    }

    return $refunds;


})->depends('can create a new order', 'can agree refund a order');


test('can refund payment fail', function (Order $order, $refunds = []) {

    foreach ($refunds as $refundId) {
        // 根据退款单 查询支付单
        $refund = $this->refundRepository->find($refundId);

        $orderPayment    = $refund->payments->first();
        $command         = new OrderPaymentFailCommand();
        $command->id     = $orderPayment->id;
        $command->amount = $orderPayment->payment_amount;
        $this->orderFake->fakeOrderPayment($command);

        $this->orderPaymentCommandService->fail($command);
    }

    foreach ($refunds as $refundId) {
        // 根据退款单 查询支付单
        $refund       = $this->refundRepository->find($refundId);
        $orderPayment = $refund->payments->first();


        $this->assertEquals(PaymentStatusEnum::FAIL->value, $orderPayment->status->value, '支付状态不正确');

    }


})->depends('can create a new order', 'can refund payment paying');


test('can refund payment paid', function (Order $order, $refunds = []) {

    foreach ($refunds as $refundId) {
        // 根据退款单 查询支付单
        $refund = $this->refundRepository->find($refundId);

        $orderPayment    = $refund->payments->first();
        $command         = new OrderPaymentPaidCommand();
        $command->id     = $orderPayment->id;
        $command->amount = $orderPayment->payment_amount;
        $this->orderFake->fakeOrderPayment($command);

        $this->orderPaymentCommandService->paid($command);
    }

    foreach ($refunds as $refundId) {
        // 根据退款单 查询支付单
        $refund       = $this->refundRepository->find($refundId);
        $orderPayment = $refund->payments->first();


        $this->assertEquals(PaymentStatusEnum::PAID->value, $orderPayment->status->value, '支付状态不正确');

    }


})->depends('can create a new order', 'can refund payment paying');



