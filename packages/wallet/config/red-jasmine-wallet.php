<?php

return [
    //
    /**
     * 钱包类型
     */
    'wallets' => [

        // 余额钱包
        'balance'    =>
            [
                // 货币配置
                'currency'            => 'ZYE',
                'name'                => '余额钱包',
                'code'                => 156,
                'minorUnit'           => 2,
                'subunit'             => 100,
                'symbol'              => '¥',
                'symbol_first'        => true,
                'decimal_mark'        => '.',
                'thousands_separator' => ',',
                // 钱包配置
                'type'                => 'balance',
                'description'         => '用户余额钱包',
                'user_types'          => [
                    'user'
                ],
                // 充值配置
                'recharge'            => [
                    'state'      => true,
                    'currencies' => [
                        [
                            'currency'      => 'CNY',
                            'exchange_rate' => 1, // 兑换汇率=钱包币种/当前币种
                            'fee_rate'      => 0,// 手续费
                        ]
                    ]
                ],
                // 提现配置
                'withdrawal'          => [
                    'state'      => false,
                    'currencies' => [
                        [
                            'currency'      => 'CNY',
                            'exchange_rate' => 1, // 兑换汇率=钱包币种/当前币种
                            'fee_rate'      => 0, // 手续费
                        ]
                    ]
                ],
            ],

        // 佣金钱包
        'commission' =>
            [
                // 货币配置
                'currency'            => 'ZCM',
                'name'                => '佣金钱包',
                'code'                => 156,
                'minorUnit'           => 2,
                'subunit'             => 100,
                'symbol'              => '¥',
                'symbol_first'        => true,
                'decimal_mark'        => '.',
                'thousands_separator' => ',',
                // 钱包配置
                'type'                => 'commission',
                'description'         => '佣金钱包',
                'user_types'          => [
                    'user'
                ],
                // 充值配置
                'recharge'            => [
                    'state'      => false,
                    'currencies' => [
                        [
                            'currency'      => 'CNY',
                            'exchange_rate' => 1, // 兑换汇率=钱包币种/当前币种
                            'fee_rate'      => 0,// 手续费
                        ]
                    ]
                ],
                // 提现配置
                'withdrawal'          => [
                    'state'      => true,
                    'currencies' => [
                        [
                            'currency'      => 'CNY',
                            'exchange_rate' => 1, // 兑换汇率=钱包币种/当前币种
                            'fee_rate'      => '0.06', // 手续费
                        ]
                    ]
                ],
            ],
        // 积分钱包
        'integral'   =>
            [
                // 货币配置
                'currency'            => 'ZJF',
                'name'                => '积分钱包',
                'code'                => 971,
                'minorUnit'           => 0,
                'subunit'             => 1,
                'symbol'              => '؋',
                'symbol_first'        => false,
                'decimal_mark'        => '.',
                'thousands_separator' => ',',
                // 钱包配置
                'type'                => 'integral',
                'description'         => '用户积分钱包',
                'user_types'          => [
                    'user'
                ],

                // 充值配置
                'recharge'            => [
                    'state'      => false,
                    'currencies' => [

                    ]
                ],
                // 提现配置
                'withdrawal'          => [
                    'state'      => false,
                    'currencies' => [
                    ]
                ],
            ],

    ]


    ,


    'pipelines' => [
        'recharges'   => [
            'create' => [

            ],
        ],
        'withdrawals' => [
            'create' => []
        ],
    ],
];
