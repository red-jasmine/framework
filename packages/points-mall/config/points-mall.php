<?php

return [
    'exchange' => [
        'min_points' => 1,                    // 最小兑换积分
        'max_points_per_order' => 10000,      // 单次最大兑换积分
        'exchange_limit_enabled' => true,      // 是否启用兑换限制
        'auto_confirm_timeout' => 24 * 60,    // 自动确认超时时间（分钟）
    ],
    
    'wallet' => [
        'type' => 'integral',                 // 积分钱包类型
        'currency' => 'ZJF',                  // 积分货币代码
    ],
    
    'order' => [
        'auto_create_logistics' => true,      // 是否自动创建物流订单
        'order_type' => 'points_exchange',    // 订单类型
        'currency' => 'CNY',                  // 现金部分使用人民币
        'payment_method' => 'mixed',          // 混合支付
    ],
    
    // 支付配置
    'payment' => [
        'merchant_app_id' => env('POINTS_MALL_MERCHANT_APP_ID'),
        'supported_methods' => [
            'wallet' => '钱包支付',
            'alipay' => '支付宝',
            'wechat' => '微信支付',
            'bank_card' => '银行卡',
        ],
        'default_method' => 'wallet',
    ],
    
    // 积分兑换比例配置
    'points_rate' => [
        'points_to_cny' => 0.01,              // 1积分 = 0.01元
        'cny_to_points' => 100,               // 1元 = 100积分
    ],
    
    // 订单状态同步配置
    'order_sync' => [
        'enabled' => true,
        'events' => [
            'created',
            'paid', 
            'accepted',
            'shipped',
            'finished',
            'canceled',
        ],
    ],
    
    // 多用户支持配置
    'multi_tenant' => [
        'enabled' => true,                    // 是否启用多用户支持
        'owner_types' => [                    // 支持的所属者类型
            'user' => '用户',
            'shop' => '商家',
            'admin' => '管理员',
        ],
        'data_isolation' => true,             // 是否启用数据隔离
    ],
    
    // 商品源配置
    'product_source' => [
        'enabled' => true,                    // 是否启用商品源关联
        'supported_types' => [                // 支持的商品源类型
            'product' => '商品',
            'service' => '服务',
            'virtual' => '虚拟商品',
        ],
        'auto_sync' => true,                  // 是否自动同步商品源信息
        'sync_fields' => [                    // 同步的字段
            'title',
            'description',
            'image',
        ],
    ],
    
    // 库存管理配置
    'stock' => [
        'enable_lock_stock' => true,          // 是否启用锁定库存
        'enable_safety_stock' => true,        // 是否启用安全库存
        'default_safety_stock' => 5,          // 默认安全库存
        'lock_timeout' => 30 * 60,            // 锁定库存超时时间（秒）
    ],

    // 外部服务配置
    'product_service_url' => env('POINTS_MALL_PRODUCT_SERVICE_URL', 'http://localhost:8001'),
    'wallet_service_url' => env('POINTS_MALL_WALLET_SERVICE_URL', 'http://localhost:8002'),
    'order_service_url' => env('POINTS_MALL_ORDER_SERVICE_URL', 'http://localhost:8003'),
    'payment_service_url' => env('POINTS_MALL_PAYMENT_SERVICE_URL', 'http://localhost:8004'),

    // 缓存配置
    'cache' => [
        'enabled' => true,
        'ttl' => 3600,                        // 缓存时间（秒）
        'prefix' => 'points_mall:',           // 缓存前缀
    ],

    // 日志配置
    'logging' => [
        'enabled' => true,
        'channel' => 'points_mall',           // 日志通道
        'level' => 'info',                    // 日志级别
    ],

    // 监控配置
    'monitoring' => [
        'enabled' => true,
        'metrics' => [
            'exchange_success_rate',           // 兑换成功率
            'exchange_response_time',          // 兑换响应时间
            'points_usage_rate',              // 积分使用率
            'product_conversion_rate',        // 商品转化率
            'mixed_payment_usage_rate',       // 混合支付使用率
        ],
    ],
]; 