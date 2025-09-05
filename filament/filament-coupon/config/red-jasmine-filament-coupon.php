<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | 优惠券管理配置
    |--------------------------------------------------------------------------
    |
    | 这里配置优惠券管理面板的相关选项
    |
    */
    
    'cluster_navigation_icon' => 'heroicon-o-ticket',
    'cluster_navigation_sort' => null,
    'cluster_navigation_group' => null,
    
    /*
    |--------------------------------------------------------------------------
    | 资源配置
    |--------------------------------------------------------------------------
    |
    | 配置各个资源的显示选项
    |
    */
    
    'resources' => [
        'coupon' => [
            'enabled' => true,
            'navigation_sort' => 1,
            'navigation_group' => null,
        ],
        'user_coupon' => [
            'enabled' => true,
            'navigation_sort' => 2,
            'navigation_group' => null,
        ],
        'coupon_usage' => [
            'enabled' => true,
            'navigation_sort' => 3,
            'navigation_group' => null,
        ],
        'coupon_issue_statistic' => [
            'enabled' => true,
            'navigation_sort' => 4,
            'navigation_group' => null,
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | 表单配置
    |--------------------------------------------------------------------------
    |
    | 配置表单的默认选项
    |
    */
    
    'form' => [
        'coupon' => [
            'sections' => [
                'basic_info' => true,
                'discount_settings' => true,
                'validity_settings' => true,
                'rules_settings' => true,
                'operate_settings' => true,
            ],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | 表格配置
    |--------------------------------------------------------------------------
    |
    | 配置表格的默认选项
    |
    */
    
    'table' => [
        'per_page' => 10,
        'default_sort' => [
            'column' => 'created_at',
            'direction' => 'desc',
        ],
    ],
    
]; 