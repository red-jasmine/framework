<?php
return [
    'labels'  => [
        'service' => '服务保障',
    ],
    'fields'  => [
        'id'          => '服务ID',
        'name'        => '名称',
        'description' => '描述',
        'cluster'     => '群簇',
        'icon'        => '图标',
        'sort'        => '排序',
        'color'       => '颜色',
        'is_show'     => '是否展示',
        'status'      => '状态',
        'created_at'  => '创建时间',
        'updated_at'  => '更新时间',
        'deleted_at'  => '删除时间',
        'creator_type' => '创建者类型',
        'creator_id'   => '创建者UID',
        'updater_type' => '更新者类型',
        'updater_id'   => '更新者UID',
    ],
    'enums'   => [
        'status' => [
            'enable'  => '启用',
            'disable' => '禁用',
        ],
    ],
    'options' => [
    ],
];
