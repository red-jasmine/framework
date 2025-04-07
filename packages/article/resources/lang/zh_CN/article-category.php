<?php
return [

    'labels'  => [
        'article-category' => '文章分类'
    ],
    'fields'  => [
        'id'          => '类目ID',
        'parent_id'   => '父级类目',
        'name'        => '类目名称',
        'description' => '描述',
        'image'       => '图片',
        'cluster'     => '群簇',
        'sort'        => '排序',
        'is_leaf'     => '是否叶子类目',
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