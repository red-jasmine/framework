<?php

namespace RedJasmine\Support\Foundation\Service;


class ResourceService extends Service
{
    protected static array $actions = [
        'create' => Actions\ResourceCreateAction::class,
        'query'  => Actions\ResourceQueryAction::class,
        'update' => Actions\ResourceUpdateAction::class,
        'delete' => Actions\ResourceDeleteAction::class,
    ];

}
