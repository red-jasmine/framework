<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 公告领域配置
    |--------------------------------------------------------------------------
    |
    | 这里包含了公告领域的所有配置选项
    |
    */

    // 默认业务线
    'default_biz' => env('ANNOUNCEMENT_DEFAULT_BIZ', 'default'),

    // 支持的业务线列表
    'supported_biz' => [
        'default' => '默认业务线',
        'platform' => '平台业务线',
        'merchant' => '商家业务线',
    ],

    // 公告状态配置
    'status' => [
        'draft' => '草稿',
        'published' => '已发布',
        'revoked' => '已撤销',
    ],

    // 审批状态配置
    'approval_status' => [
        'pending' => '待审批',
        'approved' => '已通过',
        'rejected' => '已拒绝',
    ],

    // 发布渠道配置
    'channels' => [
        'web' => '网站',
        'app' => 'APP',
        'sms' => '短信',
        'email' => '邮件',
        'push' => '推送',
    ],

    // 内容类型配置
    'content_types' => [
        'text' => '纯文本',
        'rich' => '富文本',
        'markdown' => 'Markdown',
    ],

    // 分页配置
    'pagination' => [
        'per_page' => 15,
        'max_per_page' => 100,
    ],

    // 缓存配置
    'cache' => [
        'enabled' => env('ANNOUNCEMENT_CACHE_ENABLED', true),
        'ttl' => env('ANNOUNCEMENT_CACHE_TTL', 3600), // 1小时
        'prefix' => 'announcement:',
    ],

    // 文件上传配置
    'upload' => [
        'disk' => env('ANNOUNCEMENT_UPLOAD_DISK', 'public'),
        'path' => 'announcements',
        'max_size' => 10240, // 10MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
    ],
];
