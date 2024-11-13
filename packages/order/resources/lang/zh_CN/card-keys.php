<?php


return [
    'label'  => '卡密',
    'labels' => [
        'order-card-keys' => '卡密',

    ],
    'fields' => [
        'id'               => '订单卡密编号',
        'order_product_id' => '订单商品项编号',
        'quantity'         => '数量',
        'content_type'     => '卡密类型',
        'content'          => '卡密内容',
        'source_type'      => '来源类型',
        'source_id'        => '来源ID',
        'status'           => '状态',
        'created_at'       => '创建时间',

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
