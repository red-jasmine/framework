<?php


return [

    'labels' => [
        'order-logistics' => '物流信息',

    ],
    'fields' => [
        'seller_type'          => '卖家类型',
        'seller_id'            => '卖家ID',
        'seller_nickname'      => '卖家昵称',
        'buyer_type'           => '卖家类型',
        'buyer_id'             => '卖家ID',
        'buyer_nickname'       => '卖家昵称',
        'id'                   => '订单支付单编号',
        'entity_type'       => '发货单类型',
        'entity_id'       => '单号',
        'order_product_id'     => '订单商品项编号',
        'shipper'              => '发货方',
        'status'               => '状态',
        'express_company_code' => '快递公司',
        'express_no'           => '快递单号',
        'shipping_time'        => '发货时间',
        'collect_time'         => '揽收时间',
        'dispatch_time'        => '派送时间',
        'signed_time'          => '签收时间',


    ],
    'enums'  => [
        'status' => [
            'created'              => '已创建',
            'recreated'            => '已取消',
            'cancelled'            => '重新创建',
            'closed'               => '已关闭',
            'sending'              => '等候发送',
            'accepting'            => '等待接单',
            'accepted'             => '已接单',
            'rejected'             => '不接单',
            'pick_up'              => '揽收成功',
            'pick_up_failed'       => '揽收失败',
            'lost'                 => '丢单',
            'rejected_by_receiver' => '拒签',
            'signed'               => '已签收',
        ],

        'shipper'        => [
            'buyer'  => '买家',
            'seller' => '卖家'

        ]

    ],

    'scopes'  => [

    ],
    'actions' => [

    ],
];
