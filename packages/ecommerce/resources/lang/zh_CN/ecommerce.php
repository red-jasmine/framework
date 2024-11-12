<?php


return [

    'labels' => [],

    'fields' => [

        'product_type'              => '商品类型',
        'shipping_type'             => '发货方式',
        'order_quantity_limit_type' => '下单限制',
        'refund_type'               => '售后类型',

        'after_sales_service' => [
            'refund_type'     => '售后类型',
            'allow_stage'     => '允许阶段',
            'time'            => '时间限制',
            'time_limit'      => '时长',
            'time_limit_unit' => '时间单位',
        ],
    ],

    'enums' => [

        'product_type' => [
            'goods'   => '普通',
            'virtual' => '虚拟',
            'ticket'  => '票据',
            'service' => '服务',
        ],

        'shipping_type'             => [
            'logistics' => '快递',
            'dummy'     => '虚拟',
            'cdk'       => '卡密',
            'delivery'  => '配送',
            'none'      => '免发',
        ],
        'order_quantity_limit_type' => [

            'unlimited' => '不限制',
            'once'      => '单次',
            'lifetime'  => '终身',
            'day'       => '按天',
            'week'      => '按周',
            'month'     => '按月',
            'year'      => '按年',
        ],

        'refund_type' => [
            'refund'              => '仅退款',
            'return_goods_refund' => '退货退款',
            'exchange'            => '换货',
            'warranty'            => '保修',
            'reshipment'          => '补发',
        ],

    ],

];
