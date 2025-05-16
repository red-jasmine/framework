<?php
return [

    'labels'  => [
        'title' => '标签分类'
    ],
    'fields'  => [
        'id'          => 'ID',
        'parent_id'   => '父级ID',
        'name'        => '类目名称',
        'description' => '描述',
        'image'       => '图片',
        'cluster'     => '群簇',
        'sort'        => '排序',
        'is_leaf'     => '叶子节点',
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