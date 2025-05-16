<?php
return [
    'labels' => [
        'title' => '标签'
    ],


    'fields' => [

        'id'          => 'ID',
        'name'        => '标题',
        'icon'        => '图标',
        'color'       => '颜色',
        'cluster'     => '群簇',
        'description' => '描述',
        'status'      => '状态',
        'category_id' => '分类ID',
        'is_show'     => '是否展示',
        'is_public'   => '是否公开',
        'sort'        => '排序',
        'version'     => '版本',

    ],

    'enums' => [
        'status' => [
            'enable'  => '启用',
            'disable' => '停用',
        ],

    ],

    'relations' => [
        'category' => '分类'

    ],
];