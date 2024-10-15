<?php

return [
    //

    'actions' => [
        'recharges'   => [
            'create' => \RedJasmine\Wallet\Actions\Recharges\RechargeCreateAction::class,
            'paid'   => \RedJasmine\Wallet\Actions\Recharges\RechargePaidAction::class,
        ],
        'withdrawals' => [
            'create' => \RedJasmine\Wallet\Actions\Withdrawals\WithdrawalCreateAction::class
        ],

    ],

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
