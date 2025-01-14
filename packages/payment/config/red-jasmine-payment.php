<?php

use RedJasmine\Payment\Channels\AlipayChannel;

return [
    //

    'tables'                  => [
        'connection' => null,
        'prefix'     => 'jasmine_',
    ],


    // 退款申请后 查询间隔
    'refund_query_interval'   => 60, // 单位m秒
    // 转账后查询间隔
    'transfer_query_interval' => 60, // 单位m秒
];
