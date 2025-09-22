<?php

return [
    'labels' => [
        'title' => '项目角色管理'
    ],
    'fields' => [
        'id' => '角色ID',
        'project_id' => '项目ID',
        'name' => '角色名称',
        'code' => '角色代码',
        'description' => '描述',
        'is_system' => '系统角色',
        'permissions' => '权限',
        'sort' => '排序',
        'status' => '状态',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],
    'enums' => [
        'status' => [
            'active' => '激活',
            'inactive' => '禁用',
        ],
    ],
    'commands' => [
        'create' => '创建',
        'update' => '更新',
        'delete' => '删除',
        'activate' => '激活',
        'deactivate' => '禁用',
    ],
];
