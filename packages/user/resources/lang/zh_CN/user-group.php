<?php
return [
    'labels' => [

        'title' => '分组'
    ],


    'fields' => [

        'id'          => 'ID',
        'parent_id'   => '父级ID',
        'name'        => '名称',
        'description' => '描述',
        'image'       => '图片',
        'cluster'     => '群簇',
        'sort'        => '排序',
        'is_leaf'     => '叶子节点',
        'is_show'     => '是否显示',
        'status'      => '状态',
        'extra'       => '扩展字段',

    ],

    'enums' => [
        'status' => [
            'enable'  => '启用',
            'disable' => '停用',
        ],

    ],

    'relations' => [
        'parent' => '父级'

    ],
];