'<?php


use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Application\UserCases\Commands\OrderPayingCommand;
use RedJasmine\Order\Domain\Events\OrderPayingEvent;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Tests\Feature\Order\Fixtures\OrderFake;


beforeEach(function () {

    $this->orderCommandService = app(OrderCommandService::class);
    //
});

test('can crate a new order', function () {

    $orderFake            = new OrderFake();
    $orderFake->orderType = OrderTypeEnum::STANDARD;
    $command              = OrderCreateCommand::from($orderFake->order());


    $result = $this->orderCommandService->create($command);

    $this->assertInstanceOf(Order::class, $result, '创建订单失败');


    $this->order = $result;

    return $result;
});


test('cna paying a order', function (Order $order) {


    Event::fake();

    $command = OrderPayingCommand::from(
        [
            'id'     => $order->id,
            'amount' => $order->payable_amount

        ]

    );


    $result = $this->orderCommandService->paying($command);

    \Illuminate\Support\Facades\Event::assertDispatched(OrderPayingEvent::class, null);

    $this->assertInstanceOf(OrderPayment::class, $result, '创建支付记录失败');


    return $result;

})->depends('can crate a new order');



