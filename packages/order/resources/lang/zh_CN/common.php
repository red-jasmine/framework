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
    ],
    'enums'  => [
        'entity_type' => [
            'order'  => '订单',
            'refund' => '售后',
        ],
    ],

];
