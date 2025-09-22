<?php

return [
    'labels' => [
        'title' => '项目成员管理'
    ],
    'fields' => [
        'id' => '成员ID',
        'project_id' => '项目ID',
        'member_type' => '成员类型',
        'member_id' => '成员ID',
        'status' => '状态',
        'joined_at' => '加入时间',
        'left_at' => '离开时间',
        'invited_by_type' => '邀请人类型',
        'invited_by_id' => '邀请人ID',
        'permissions' => '权限',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],
    'enums' => [
        'status' => [
            'pending' => '待确认',
            'active' => '正常',
            'paused' => '暂停',
            'left' => '已退出',
        ],
    ],
    'commands' => [
        'join' => '加入',
        'leave' => '离开',
        'activate' => '激活',
        'pause' => '暂停',
    ],
];
