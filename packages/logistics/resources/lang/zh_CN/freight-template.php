<?php
return [


    'labels' => [
        'title' => '运费模板'
    ],
    'fields' => [
        'id'          => 'ID',
        'name'        => '名称',
        'charge_type' => '计费类型',
        'is_free'     => '是否免费',
        'sort'        => '排序',
        'status'      => '状态',

        'strategies' => [
            'type'              => '类型',
            'is_all_regions'    => '全部区域',
            'regions'           => '区域',
            'standard_fee'      => '标准费用',
            'standard_quantity' => '标准数量',
            'extra_quantity'    => '额外数量',
            'extra_fee'         => '额外费用',
        ],
        'regions'    => [
            'type' => '区域类型',
        ],
    ],

    'enums' => [

        'region_type' => [
            'charge'      => '收费',
            'free'        => '免费',
            'unreachable' => '不可达',
        ],

    ],

    'relations' => [

        'strategies' => '策略',
        'regions'    => '区域',
    ],
];