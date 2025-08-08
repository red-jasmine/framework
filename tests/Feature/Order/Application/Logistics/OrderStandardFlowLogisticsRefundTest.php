'<?php


use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPaidCommand;
use RedJasmine\Order\Application\Services\Orders\Commands\OrderPayingCommand;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeRefundCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundAgreeReturnGoodsCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundCreateCommand;
use RedJasmine\Order\Application\Services\Refunds\Commands\RefundReturnGoodsCommand;
use RedJasmine\Order\Application\Services\Refunds\RefundApplicationService;
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
    $this->orderCommandService  = app(OrderApplicationService::class);
    $this->refundCommandService = app(RefundApplicationService::class);
    $this->refundRepository     = app(RefundRepositoryInterface::class);
    $this->refundReadRepository = app(RefundReadRepositoryInterface::class);

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


// 申请退款

test('can apply refund a order', function (Order $order, OrderPayment $orderPayment) {

    $commands = [];


    $refunds = [];
    foreach ($order->products as $product) {
        $command                 = new RefundCreateCommand;
        $command->orderNo        = $order->order_no;
        $command->orderProductNo = $product->order_product_no;
        $command->refundType     = RefundTypeEnum::REFUND;
        $command->refundAmount   = $product->payment_amount;
        $command->reason         = '不想要了';
        $command->description    = fake()->sentence;
        $command->outerRefundId  = fake()->numerify('######');
        $command->images         = [fake()->imageUrl, fake()->imageUrl, fake()->imageUrl,];

        $commands [] = $command;


        $refunds[] = $this->refundCommandService->create($command);
    }


    $order = $this->orderRepository->find($order->id);

    $refundModels = collect([]);
    foreach ($refunds as $refundNo) {
        $refund = $this->refundRepository->findByNo($refundNo);
        $refundModels->add($refund);
        $this->assertEquals(RefundStatusEnum::PENDING->value, $refund->refund_status->value, '退款状态不正确');
    }
    $sumRefundAmount = Money::sum(...$refundModels->pluck('total_refund_amount'));
    $this->assertTrue($order->payable_amount->equals($sumRefundAmount));

    return $refunds;


})->depends('can create a new order', 'cna paying a order', 'can paid a order');

test('can shipped a order', function (Order $order, OrderPayment $orderPayment, $result, $refunds) {


    $command = $this->orderFake->shippingLogistics([
        'order_no'       => $order->order_no,
        'order_products' => $order->products->pluck('order_product_no')->toArray()
    ]);

    $this->orderCommandService->logisticsShipping($command);

    /**
     * @var $order Order
     */
    $order = $this->orderRepository->find($order->id);

    $this->assertEquals($order->order_status, OrderStatusEnum::CONFIRMING, '订单状态');
    $this->assertEquals($order->shipping_status, ShippingStatusEnum::SHIPPED, '发货状态');


    // 查询刚刚的退款单

    foreach ($refunds as $refundNo) {
        $refund = $this->refundRepository->findByNo($refundNo);

        $this->assertEquals($refund->refund_status->value,
            RefundStatusEnum::REJECTED->value, '退款状态');
    }


    return $order;
})->depends('can create a new order', 'cna paying a order', 'can paid a order', 'can apply refund a order');


// 申请退货退款

test('can apply return goods refund', function (Order $order, OrderPayment $orderPayment) {

    $commands = [];


    foreach ($order->products as $product) {
        $command                 = new RefundCreateCommand;
        $command->orderNo        = $order->order_no;
        $command->orderProductNo = $product->order_product_no;
        $command->refundType     = RefundTypeEnum::RETURN_GOODS_REFUND;
        $command->refundAmount   = $product->payment_amount;
        $command->reason         = '不想要了';
        $command->description    = fake()->sentence;
        $command->outerRefundId  = fake()->numerify('######');
        $command->images         = [fake()->imageUrl, fake()->imageUrl, fake()->imageUrl,];

        $commands [] = $command;


        $refunds[] = $this->refundCommandService->create($command);
    }


    $order = $this->orderRepository->find($order->id);

    $refundModels = collect([]);
    foreach ($refunds as $refundNo) {
        $refund = $this->refundRepository->findByNo($refundNo);
        $refundModels->add($refund);
        $this->assertEquals(RefundStatusEnum::PENDING->value, $refund->refund_status->value, '退款状态不正确');
    }


    return $refunds;


})->depends('can create a new order', 'cna paying a order', 'can paid a order');


test('can seller agree return goods refund', function (Order $order, $refunds) {
    foreach ($refunds as $refundNo) {
        $command           = new RefundAgreeReturnGoodsCommand();
        $command->refundNo = $refundNo;

        $this->refundCommandService->agreeReturnGoods($command);
    }
    foreach ($refunds as $refundNo) {
        $refund = $this->refundRepository->findByNo($refundNo);
        $this->assertEquals($refund->refund_status, RefundStatusEnum::RETURNING, ' 退款状态不正确');
    }
    return $refunds;
})->depends('can create a new order', 'can apply return goods refund');


// 买家退货

test('can buyer return goods', function (Order $order, $refunds) {

    foreach ($refunds as $refundNo) {
        $command                       = new RefundReturnGoodsCommand();
        $command->refundNo                   = $refundNo;
        $command->logisticsCompanyCode = fake()->randomElement(['shunfeng', 'yuantong',]);
        $command->logisticsNo          = fake()->numerify('##########');
        $this->refundCommandService->returnGoods($command);
    }

    foreach ($refunds as $refundNo) {
        $refund = $this->refundRepository->findByNo($refundNo);
        $this->assertEquals($refund->refund_status, RefundStatusEnum::CHECKING, ' 退款状态不正确');
    }
    return $refunds;


})->depends('can create a new order', 'can seller agree return goods refund');

// 同意退款
test('can seller agree refund', function (Order $order, $refunds) {
    // TODO 邮费
    foreach ($refunds as $refundNo) {
        $refund            = $this->refundRepository->findByNo($refundNo);
        $command           = new RefundAgreeRefundCommand();
        $command->refundNo = $refundNo;
        $command->amount   = $refund->refund_amount;
        $this->refundCommandService->agreeRefund($command);
    }

    foreach ($refunds as $refundNo) {
        $refund = $this->refundRepository->findByNo($refundNo);
        $this->assertEquals($refund->refund_status, RefundStatusEnum::FINISHED, ' 退款状态不正确');
    }
    return $refunds;
})->depends('can create a new order', 'can buyer return goods');




