<?php

return [
    'labels' => [
        'title' => '项目管理'
    ],
    'fields' => [
        'id' => '项目ID',
        'owner' => '所属者',
        'owner_type' => '所属者类型',
        'owner_id' => '所属者ID',
        'parent_id' => '父项目ID',
        'name' => '项目名称',
        'short_name' => '简称',
        'description' => '描述',
        'code' => '项目代码',
        'project_type' => '项目类型',
        'status' => '状态',
        'sort' => '排序',
        'config' => '配置',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
        'deleted_at' => '删除时间',
    ],
    'enums' => [
        'project_type' => [
            'standard' => '标准项目',
            'template' => '模板项目',
            'temporary' => '临时项目',
        ],
        'status' => [
            'draft' => '草稿',
            'active' => '激活',
            'paused' => '暂停',
            'archived' => '归档',
        ],
    ],
    'commands' => [
        'activate' => '激活',
        'pause' => '暂停',
        'archive' => '归档',
    ],
];
