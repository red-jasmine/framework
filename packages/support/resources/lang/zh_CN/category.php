<?php
return [

    'labels'  => [
        'category' => '分类'
    ],
    'fields'  => [
        'id'          => '分类ID',
        'parent_id'   => '父级分类',
        'name'        => '分类名称',
        'description' => '描述',
        'image'       => '图片',
        'cluster'     => '群簇',
        'sort'        => '排序',
        'is_leaf'     => '是否叶子分类',
        'is_show'     => '是否显示',
        'status'      => '状态',
    ],
    'enums'   => [
        'status' => [
            'disable' => '启用',
            'enable'  => '停用',
        ],

    ],
    'options' => [


    ],


];