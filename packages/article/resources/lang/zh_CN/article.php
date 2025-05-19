<?php

return [

    'labels'   => [

        'title'   => '文章内容'

    ],
    'fields'   => [
        'id'              => '文章ID',
        'owner'           => '所属者',
        'owner_type'      => '所属者类型',
        'owner_id'        => '所属者ID',
        'title'           => '标题',
        'image'           => '图片',
        'description'     => '描述',
        'keywords'        => '关键字',
        'status'          => '状态',
        'category'        => '分类',
        'category_id'     => '分类',
        'is_top'          => '是否置顶',
        'is_show'         => '是否展示',
        'sort'            => '排序',
        'publish_time'    => '发布时间',
        'approval_status' => '审批状态',
        'version'         => '版本',
        'content_type'    => '内容类型',
        'content'         => '内容',
        'tags'            => '标签',
    ],
    'enums'    => [
        'content_type' => [
            'markdown' => 'markdown',
            'rich'     => '富文本',
            'text'     => '文字',
        ],
        'status'       => [
            'draft'     => '草稿',
            'published' => '已发布',
            'deleted'   => '已删除',
        ],

    ],
    'commands' => [
        'publish' => '发布'

    ],


];