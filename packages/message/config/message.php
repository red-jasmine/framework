<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Message Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the message system.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | Default configuration for message system
    |
    */
    'defaults' => [
        'push_channels' => ['in_app'], // 默认推送渠道
        'priority' => 'normal', // 默认优先级
        'expires_days' => 30, // 默认过期天数
        'max_retry_count' => 3, // 最大重试次数
    ],

    /*
    |--------------------------------------------------------------------------
    | Push Channels Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for different push channels
    |
    */
    'channels' => [
        'in_app' => [
            'enabled' => true,
            'queue' => 'message',
        ],
        'push' => [
            'enabled' => true,
            'queue' => 'push',
            'driver' => env('MESSAGE_PUSH_DRIVER', 'jpush'),
        ],
        'email' => [
            'enabled' => true,
            'queue' => 'email',
            'driver' => env('MESSAGE_EMAIL_DRIVER', 'smtp'),
        ],
        'sms' => [
            'enabled' => true,
            'queue' => 'sms',
            'driver' => env('MESSAGE_SMS_DRIVER', 'aliyun'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Business Lines Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for different business lines
    |
    */
    'business_lines' => [
        'user' => '用户端',
        'merchant' => '商家端',
        'admin' => '管理端',
        'system' => '系统端',
    ],

    /*
    |--------------------------------------------------------------------------
    | Message Types Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for message types
    |
    */
    'types' => [
        'notification' => '通知',
        'alert' => '警告',
        'reminder' => '提醒',
    ],

    /*
    |--------------------------------------------------------------------------
    | Priority Levels Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for priority levels
    |
    */
    'priorities' => [
        'low' => '低',
        'normal' => '普通',
        'high' => '高',
        'urgent' => '紧急',
    ],

    /*
    |--------------------------------------------------------------------------
    | Message Status Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for message status
    |
    */
    'status' => [
        'unread' => '未读',
        'read' => '已读',
        'archived' => '已归档',
    ],

    /*
    |--------------------------------------------------------------------------
    | Template Engine Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for template rendering
    |
    */
    'template' => [
        'engine' => 'blade', // 模板引擎
        'cache' => true, // 是否缓存渲染结果
        'cache_ttl' => 3600, // 缓存时间（秒）
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for message queues
    |
    */
    'queue' => [
        'connection' => env('MESSAGE_QUEUE_CONNECTION', 'redis'),
        'default_queue' => 'message',
        'failed_queue' => 'failed_messages',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for message caching
    |
    */
    'cache' => [
        'store' => env('MESSAGE_CACHE_STORE', 'redis'),
        'prefix' => 'message:',
        'ttl' => [
            'unread_count' => 300, // 未读消息数量缓存时间（秒）
            'category_list' => 3600, // 分类列表缓存时间（秒）
            'template_content' => 1800, // 模板内容缓存时间（秒）
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for message rate limiting
    |
    */
    'rate_limit' => [
        'enabled' => true,
        'max_per_minute' => 60, // 每分钟最大发送数量
        'max_per_hour' => 300, // 每小时最大发送数量
        'max_per_day' => 1000, // 每天最大发送数量
    ],

    /*
    |--------------------------------------------------------------------------
    | Archive Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for message archiving
    |
    */
    'archive' => [
        'enabled' => true,
        'auto_archive_days' => 90, // 自动归档天数
        'delete_archived_days' => 365, // 删除已归档消息天数
    ],
];
