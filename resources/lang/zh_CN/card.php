<?php

return [

    'labels' => [
        'card' => '电子卡密'
    ],
    'fields' => [
        'group_id'  => '分组',
        'is_loop'   => '是否循环',
        'status'    => '状态',
        'sold_time' => '出售时间',
        'content'   => '内容',
        'remarks'   => '备注',
    ],
    'enums'  => [
        'status' => [
            'enable'  => '启用',
            'disable' => '禁用',
            'sold'    => '已售',
        ],
    ],
];
