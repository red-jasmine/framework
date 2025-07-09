<?php

return [
    /**
     * 优惠券默认配置
     */
    'defaults' => [
        'currency' => 'CNY',
        'per_user_limit' => 1,
        'per_day_limit' => 100,
        'validity_days' => 30,
    ],

    /**
     * 优惠券类型配置
     */
    'discount_types' => [
        'percentage' => [
            'enabled' => true,
            'min_value' => 0.01,
            'max_value' => 0.99,
        ],
        'fixed' => [
            'enabled' => true,
            'min_value' => 0.01,
            'max_value' => 10000,
        ],
        'shipping' => [
            'enabled' => true,
            'min_value' => 0.01,
            'max_value' => 1000,
        ],
    ],

    /**
     * 门槛类型配置
     */
    'threshold_types' => [
        'order_amount' => [
            'enabled' => true,
            'min_value' => 0.01,
        ],
        'product_amount' => [
            'enabled' => true,
            'min_value' => 0.01,
        ],
        'shipping_amount' => [
            'enabled' => true,
            'min_value' => 0.01,
        ],
    ],

    /**
     * 有效期配置
     */
    'validity' => [
        'absolute' => [
            'enabled' => true,
            'max_days' => 365,
        ],
        'relative' => [
            'enabled' => true,
            'max_days' => 365,
        ],
    ],

    /**
     * 发放策略配置
     */
    'issue_strategies' => [
        'manual' => [
            'enabled' => true,
        ],
        'auto' => [
            'enabled' => true,
        ],
        'code' => [
            'enabled' => true,
        ],
    ],

    /**
     * 缓存配置
     */
    'cache' => [
        'enabled' => true,
        'ttl' => 3600,
        'prefix' => 'coupon:',
    ],

    /**
     * 队列配置
     */
    'queue' => [
        'enabled' => true,
        'connection' => 'default',
        'queue' => 'default',
    ],
];