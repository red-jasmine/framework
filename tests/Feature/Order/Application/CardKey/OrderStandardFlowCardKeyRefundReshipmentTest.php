'<?php


use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\Services\RefundCommandService;
use RedJasmine\Order\Application\UserCases\Commands\OrderConfirmCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundAgreeReshipmentCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCardKeyReshipmentCommand;
use RedJasmine\Order\Application\UserCases\Commands\Refund\RefundCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\Shipping\OrderCardKeyShippingCommand;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;
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
    $orderFake->shippingType = ShippingTypeEnum::CDK;
    $orderFake->productCount = 1;
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


test('can shipped a order', function (Order $order, OrderPayment $orderPayment, $result) {

    $commands = [];

    foreach ($order->products as $product) {
        $command = OrderCardKeyShippingCommand::from(
            [
                'id'             => $order->id,
                'orderProductId' => $product->id,
                'content'        => fake()->sentence(),
                'num'            => 1
            ]
        );

        for ($i = 1; $i <= $product->progress_total; $i++) {
            $this->orderCommandService->cardKeyShipping($command);
        }


    }

    /**
     * @var $order Order
     */
    $order = $this->orderRepository->find($order->id);

    $this->assertEquals($order->order_status, OrderStatusEnum::WAIT_BUYER_CONFIRM_GOODS, '订单状态');
    $this->assertEquals($order->shipping_status, ShippingStatusEnum::SHIPPED, '发货状态');

    foreach ($order->products as $product) {
        // 判断卡密是否和数量一致
        $this->assertEquals($product->cardKeys()->sum('num'), $product->progress_total, '卡密数量');
    }

    return $order;
})->depends('can create a new order', 'cna paying a order', 'can paid a order');


test('can confirm a order', function (Order $order) {

    $command = OrderConfirmCommand::from([ 'id' => $order->id ]);

    $this->orderCommandService->confirm($command);

    $order = $this->orderRepository->find($order->id);

    $this->assertEquals($order->order_status, OrderStatusEnum::FINISHED, '订单状态');

    return $order;

})->depends('can shipped a order');


// 测试申请补发
test('can refund a order', function (Order $order) {
    $commands = [];


    $refunds = [];
    foreach ($order->products as $product) {
        $command                 = new RefundCreateCommand;
        $command->id             = $order->id;
        $command->orderProductId = $product->id;
        $command->refundType     = RefundTypeEnum::RESHIPMENT;
        $command->refundAmount   = 0;
        $command->reason         = '补发';
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


})->depends('can confirm a order');


// 测试 同意补发

test('can agree refund reshipment', function ($refunds) {

    foreach ($refunds as $refund) {

        $command      = new RefundAgreeReshipmentCommand();
        $command->rid = $refund;
        $this->refundCommandService->agreeReshipment($command);
        $refund = $this->refundRepository->find($command->rid);
        $this->assertEquals(RefundStatusEnum::WAIT_SELLER_RESHIPMENT, $refund->refund_status, '退款状态不正确');
    }


    return $refunds;

})->depends('can refund a order');

test('can reshipment', function ($refunds) {
    foreach ($refunds as $refund) {

        $command          = new RefundCardKeyReshipmentCommand();
        $command->rid     = $refund;
        $command->content = fake()->sentence;
        $this->refundCommandService->cardKeyReshipment($command);
        $refund = $this->refundRepository->find($command->rid);
        $this->assertEquals(RefundStatusEnum::FINISHED, $refund->refund_status, '退款状态不正确');
    }


    return $refunds;
})->depends('can agree refund reshipment');
