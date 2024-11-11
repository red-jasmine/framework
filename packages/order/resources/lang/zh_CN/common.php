<?php

return [

    'fields' => [
        'order_id'         => '订单编号',
        'order_product_id' => '订单商品项编号',
        'refund_id'        => '售后编号',
        'entity_type'      => '对象类型',
        'entity_id'        => '对象编号',
        'seller_type'      => '卖家类型',
        'seller_id'        => '卖家ID',
        'seller_nickname'  => '卖家昵称',
        'buyer_type'       => '卖家类型',
        'buyer_id'         => '卖家ID',
        'buyer_nickname'   => '卖家昵称',
        'urge'             => '催单',
        'urge_time'        => '催单时间',
    ],
    'enums'  => [
        'entity_type'    => [
            'order'  => '订单',
            'refund' => '售后',
        ],
        'payment_status' => [
            'wait_pay'   => '等待支付',
            'paying'     => '支付中',
            'part_pay'   => '部分支付',
            'paid'       => '支付成功',
            'no_payment' => '无需支付',
            'fail'       => '支付失败',
        ],
    ],

];
