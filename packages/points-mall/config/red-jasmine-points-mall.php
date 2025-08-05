<?php

return [

    'wallet'  => [
        'type'     => 'integral',                 // 积分钱包类型
        'currency' => 'ZJF',                  // 积分货币代码
    ],

    // 支付配置
    'payment' => [
        'merchant_app_id' => env('POINTS_MALL_MERCHANT_APP_ID'),
    ],


]; 