<?php


return [

    'labels' => [
        'order-logistics' => '物流信息',

    ],
    'fields' => [
        'id'                     => '订单物流编号',
        'order_product_id'       => '订单商品项编号',
        'shipper'                => '发货方',
        'status'                 => '状态',
        'logistics_company_code' => '物流公司',
        'logistics_no'           => '物流单号',
        'shipping_time'          => '发货时间',
        'collect_time'           => '揽收时间',
        'dispatch_time'          => '派送时间',
        'signed_time'            => '签收时间',


    ],
    'enums'  => [
        'status' => [
            'created'       => '已创建',
            'recreated'     => '已取消',
            'cancelled'     => '重新创建',
            'closed'        => '已关闭',
            'lost'          => '丢单',
            'accepting'     => '等待接单',
            'accepted'      => '已接单',
            'rejected'      => '不接单',
            'collect'       => '揽收',
            'sending'       => '运输中',
            'dispatch'      => '派件中',
            'rejected_sign' => '拒签',
            'signed'        => '已签收',
        ],

        'shipper' => [
            'buyer'  => '买家',
            'seller' => '卖家'

        ]

    ],

    'scopes'  => [

    ],
    'actions' => [

    ],
];
