<?php


return [

    'labels' => [
        'order-payments' => '订单支付单',

    ],
    'fields' => [
        'id'                 => '订单支付单编号',
        'order_id'           => '订单编号',
        'amount_type'        => '金额类型',
        'payment_amount'     => '金额类型',
        'status'             => '支付状态',
        'payment_time'       => '支付时间',
        'payment_type'       => '支付单类型',
        'payment_id'         => '支付单 ID',
        'payment_method'     => '支付方式',
        'payment_channel'    => '支付渠道',
        'payment_channel_no' => '支付渠道单号',

    ],
    'enums'  => [
        'amount_type' => [
            'full'    => '全款',
            'deposit' => '预付',
            'tail'    => '尾款',
            'refund'  => '退款',
        ],


    ],

    'scopes'  => [

    ],
    'actions' => [

    ],
];
