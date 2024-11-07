<?php


return [

    'labels' => [
        'order-card-keys' => '订单卡密',

    ],
    'fields' => [
        'seller_type'      => '卖家类型',
        'seller_id'        => '卖家ID',
        'seller_nickname'  => '卖家昵称',
        'buyer_type'       => '卖家类型',
        'buyer_id'         => '卖家ID',
        'buyer_nickname'   => '卖家昵称',
        'id'               => '订单卡密编号',
        'order_id'         => '订单编号',
        'order_product_id' => '订单商品项编号',
        'num'              => '数量',
        'content_type'     => '卡密类型',
        'content'          => '卡密内容',
        'source_type'      => '来源类型',
        'source_id'        => '来源ID',
        'status'           => '状态',


    ],
    'enums'  => [
        'content_type' => [
            'text'   => '文本',
            'qrcode' => '二维码'
        ],


    ],

    'scopes'  => [

    ],
    'actions' => [

    ],
];
