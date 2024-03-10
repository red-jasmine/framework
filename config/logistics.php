<?php

return [
    //
    'actions' => [

        'template' => [

            'create' => \RedJasmine\Logistics\Actions\FreightTemplates\FreightTemplateCreateAction::class,
            'update' => \RedJasmine\Logistics\Actions\FreightTemplates\FreightTemplateUpdateAction::class,
        ],

    ],

    'pipelines' => [
        'template' => [
            'create' => [],

        ],
    ],
];
