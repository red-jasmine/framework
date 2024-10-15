<?php


return [

    'labels' => [],

    'enums' => [

        'product_type' => [
            'goods'   => '普通',
            'virtual' => '虚拟',
            'ticket'  => '票据',
            'service' => '服务',
        ],

        'shipping_type'             => [
            'express'  => '快递',
            'dummy'    => '虚拟',
            'cdk'      => '卡密',
            'delivery' => '配送',
            'none'     => '免发',
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


    ],

];
