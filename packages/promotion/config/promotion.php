<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 活动配置
    |--------------------------------------------------------------------------
    |
    | 这里配置活动相关的默认设置
    |
    */

    'defaults' => [
        'activity_status' => 'draft',
        'product_status' => 'pending',
        'sku_status' => 'active',
    ],

    /*
    |--------------------------------------------------------------------------
    | 活动类型配置
    |--------------------------------------------------------------------------
    |
    | 支持的活动类型及其配置
    |
    */

    'types' => [
        'flash_sale' => [
            'name' => '秒杀活动',
            'description' => '限时秒杀，价格优惠',
            'features' => ['time_limit', 'stock_limit', 'price_discount'],
        ],
        'group_buying' => [
            'name' => '拼团活动',
            'description' => '多人拼团，享受优惠',
            'features' => ['group_limit', 'time_limit', 'price_discount'],
        ],
        'bargain' => [
            'name' => '砍价活动',
            'description' => '邀请好友砍价',
            'features' => ['invite_limit', 'bargain_limit', 'price_reduction'],
        ],
        'discount' => [
            'name' => '折扣活动',
            'description' => '商品折扣促销',
            'features' => ['discount_rate', 'time_limit'],
        ],
        'full_reduction' => [
            'name' => '满减活动',
            'description' => '满额减免',
            'features' => ['amount_threshold', 'reduction_amount'],
        ],
        'bundle' => [
            'name' => '凑单活动',
            'description' => '凑单享受优惠',
            'features' => ['bundle_rules', 'time_limit'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 活动状态配置
    |--------------------------------------------------------------------------
    |
    | 活动状态流转规则
    |
    */

    'status_flow' => [
        'draft' => ['pending', 'cancelled'],
        'pending' => ['published', 'cancelled'],
        'published' => ['warming', 'cancelled'],
        'warming' => ['running', 'cancelled'],
        'running' => ['paused', 'ended'],
        'paused' => ['running', 'ended', 'cancelled'],
        'ended' => [],
        'cancelled' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | 商品参与模式配置
    |--------------------------------------------------------------------------
    |
    | SKU参与模式和价格设置模式
    |
    */

    'participation_modes' => [
        'sku_modes' => [
            'all_skus' => '所有SKU参与',
            'specific_skus' => '指定SKU参与',
        ],
        'price_modes' => [
            'unified' => '统一设置',
            'individual' => '独立设置',
        ],
        'stock_modes' => [
            'unified' => '统一管理',
            'individual' => '独立管理',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 活动规则配置
    |--------------------------------------------------------------------------
    |
    | 默认活动规则设置
    |
    */

    'default_rules' => [
        'user_participation_limit' => 1, // 用户参与次数限制
        'product_purchase_limit' => 10,  // 商品限购数量
        'allow_overlay' => false,        // 是否允许优惠叠加
        'new_user_only' => false,        // 是否仅限新用户
        'member_only' => false,          // 是否仅限会员
    ],

    /*
    |--------------------------------------------------------------------------
    | 统计配置
    |--------------------------------------------------------------------------
    |
    | 统计数据相关配置
    |
    */

    'statistics' => [
        'cache_ttl' => 3600, // 统计数据缓存时间（秒）
        'batch_size' => 1000, // 批量处理大小
    ],
];
