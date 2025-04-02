<?php

// config for RedJasmine/Article
return [
    'interaction' => [
        'types' => [
            'comment',
            'like',
            'dislike',
            'share',
            'view',
            'report',
            'favorite',
        ],

        'limiter' => [
            'default' => [
                'unique'   => true,
                'once'     => 1,
                'interval' => 60,
                'totals'   => null,
            ],
            'comment' => [
                'unique'   => false,
                'once'     => 1,
                'interval' => 60,
                'totals'   => null,
            ],
        ],
    ],
];
