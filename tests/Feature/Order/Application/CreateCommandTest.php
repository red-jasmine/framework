<?php



test('can crate a new order', function () {



    $orderFake =  new \RedJasmine\Tests\Feature\Order\Fixtures\OrderFake();


    dd($orderFake->order());

    $this->assertTrue(true,'是吧');

    // 构建订单 CreateCommand

    // 创建订单


});
