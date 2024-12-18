<?php

return [
    //

    'tables' => [
        'connection' => null,
        'prefix'     => 'jasmine_',
    ],


    'channels' => [
        'alipay' => [
            'name'  => '支付宝',
            'drive' => \RedJasmine\Payment\Channels\AlipayChannel::class,
            'icon'  => '',
        ],
        'wechat' => [
            'name'  => '微信',
            'drive' => \RedJasmine\Payment\Channels\AlipayChannel::class,
            'icon'  => '',
        ],
    ],
];
