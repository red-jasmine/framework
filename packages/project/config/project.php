<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Project Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the project package.
    |
    */

    'default_permissions' => [
        'project.view',
        'project.edit',
        'project.delete',
        'member.view',
        'member.invite',
        'member.remove',
        'role.view',
        'role.create',
        'role.edit',
        'role.delete',
    ],

    'system_roles' => [
        'owner' => [
            'name' => '项目所有者',
            'code' => 'owner',
            'description' => '项目所有者，拥有所有权限',
            'permissions' => ['*'], // 所有权限
        ],
        'admin' => [
            'name' => '项目管理员',
            'code' => 'admin',
            'description' => '项目管理员，拥有大部分权限',
            'permissions' => [
                'project.view',
                'project.edit',
                'member.view',
                'member.invite',
                'member.remove',
                'role.view',
                'role.create',
                'role.edit',
                'role.delete',
            ],
        ],
        'member' => [
            'name' => '项目成员',
            'code' => 'member',
            'description' => '普通项目成员',
            'permissions' => [
                'project.view',
                'member.view',
            ],
        ],
    ],

    'pagination' => [
        'per_page' => 15,
        'max_per_page' => 100,
    ],

    'code_generation' => [
        'max_length' => 20,
        'min_length' => 2,
        'prefix' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | 项目树配置
    |--------------------------------------------------------------------------
    */
    'tree' => [
        'max_depth' => 10,
        'max_children' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | 事件配置
    |--------------------------------------------------------------------------
    */
    'events' => [
        'enable_logging' => true,
        'log_channel' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | 权限配置
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'cache_ttl' => 3600, // 权限缓存时间（秒）
        'enable_cache' => true,
    ],
];
