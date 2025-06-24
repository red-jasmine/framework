<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 邀请码配置
    |--------------------------------------------------------------------------
    */
    'code' => [
        // 默认邀请码长度
        'default_length' => 8,
        
        // 自定义邀请码长度限制
        'custom_min_length' => 4,
        'custom_max_length' => 20,
        
        // 系统生成邀请码字符集（排除易混淆字符）
        'charset' => '23456789ABCDEFGHJKLMNPQRSTUVWXYZ',
        
        // 邀请码生成重试次数
        'generate_retry_times' => 3,
        
        // 敏感词过滤
        'forbidden_words' => [
            'admin', 'root', 'system', 'test', 'null', 'undefined'
        ],
        
        // 默认有效期（天）
        'default_expires_days' => 30,
        
        // 过期宽限期（小时）
        'expire_grace_hours' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | 邀请链接配置
    |--------------------------------------------------------------------------
    */
    'link' => [
        // 链接最大长度
        'max_length' => 2048,
        
        // 平台域名配置
        'domains' => [
            'web' => env('INVITATION_WEB_DOMAIN', 'https://example.com'),
            'h5' => env('INVITATION_H5_DOMAIN', 'https://m.example.com'),
            'miniprogram' => env('INVITATION_MINIPROGRAM_DOMAIN', 'https://mp.example.com'),
            'app' => env('INVITATION_APP_DOMAIN', 'https://app.example.com'),
        ],
        
        // 默认去向页面
        'default_destinations' => [
            'register' => '/register',
            'home' => '/',
            'product' => '/products',
            'activity' => '/activities',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 海报模板配置
    |--------------------------------------------------------------------------
    */
    'poster' => [
        // 海报存储路径
        'storage_path' => 'invitations/posters',
        
        // 二维码配置
        'qrcode' => [
            'size' => 200,
            'margin' => 10,
            'error_correction' => 'M',
        ],
        
        // 支持的图片格式
        'supported_formats' => ['jpg', 'jpeg', 'png', 'webp'],
        
        // 默认字体
        'default_font' => 'fonts/NotoSansCJK-Regular.ttc',
    ],

    /*
    |--------------------------------------------------------------------------
    | 统计配置
    |--------------------------------------------------------------------------
    */
    'statistics' => [
        // 统计数据保留天数
        'retention_days' => 1095, // 3年
        
        // 实时统计更新频率（秒）
        'realtime_update_interval' => 60,
        
        // 批量统计处理大小
        'batch_size' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | 缓存配置
    |--------------------------------------------------------------------------
    */
    'cache' => [
        // 缓存驱动
        'store' => env('INVITATION_CACHE_STORE', 'redis'),
        
        // 缓存过期时间（秒）
        'ttl' => [
            'invitation_code' => 3600, // 1小时
            'invitation_link' => 1800, // 30分钟  
            'platform_mapping' => 86400, // 24小时
            'destination_config' => 3600, // 1小时
        ],
        
        // 缓存键前缀
        'prefix' => 'invitation:',
    ],

    /*
    |--------------------------------------------------------------------------
    | 安全配置
    |--------------------------------------------------------------------------
    */
    'security' => [
        // 单IP访问频率限制（次/分钟）
        'rate_limit' => 60,
        
        // IP黑名单
        'blacklist' => [],
        
        // 是否记录访问日志
        'log_access' => true,
        
        // 敏感参数过滤
        'filter_params' => ['password', 'token', 'secret'],
    ],

]; 