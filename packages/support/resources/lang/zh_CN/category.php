<?php
return [

    'labels'  => [
        'category' => '分类'
    ],
    'fields'  => [
        'id'          => '序号',
        'parent_id'   => '父级',
        'name'        => '名称',
        'slug'        => '标记',
        'description' => '描述',
        'image'       => '图片',
        'icon'        => '图标',
        'color'       => '颜色',
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