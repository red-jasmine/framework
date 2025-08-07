<?php


return [
    'label'  => '支付',
    'labels' => [
        'order-payments' => '支付',

    ],
    'fields' => [
        'id'                 => '订单支付编号',
        'amount_type'        => '金额类型',
        'payment_amount'     => '金额类型',
        'status'             => '支付状态',
        'payment_time'       => '支付时间',
        'payment_type'       => '支付单类型',
        'payment_id'         => '支付单 ID',
        'payment_method'     => '支付方式',
        'payment_channel'    => '支付渠道',
        'payment_channel_no' => '支付渠道单号',
        'message'            => '支付信息',


        // |--------------公共部分-------------------
        'order_id'         => '订单编号',
        'order_product_id' => '订单商品项编号',
        'refund_id'        => '售后编号',
        'entity_type'      => '对象类型',
        'entity_id'        => '对象编号',
        'seller_type'      => '卖家类型',
        'seller_id'        => '卖家ID',
        'seller_nickname'  => '卖家昵称',
        'buyer_type'       => '买家类型',
        'buyer_id'         => '买家ID',
        'buyer_nickname'   => '买家昵称',
        'version'          => '版本',
        'urge'             => '催单',
        'urge_time'        => '催单时间',
        'seller'           => '卖家',
        'buyer'            => '买家',
        'channel'          => '渠道',
        'store'            => '门店',
        'guide'            => '导购',
        'products'         => '商品',
        // |--------------公共部分-------------------

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
    'commands' => [

    ],
];
