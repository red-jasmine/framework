<?php

return [
    //

    'actions' => [
        'recharges' => [
            'create' => \RedJasmine\Wallet\Actions\Recharges\RechargeCreateAction::class,
            'paid'   => \RedJasmine\Wallet\Actions\Recharges\RechargePaidAction::class,
        ],

    ],

    'pipelines' => [
        'recharges' => [
            'recharging' => [

            ],
        ],
    ],

    'recharge' => [
        'fee_ratio' => 0, // 手续费比例
    ],
];
