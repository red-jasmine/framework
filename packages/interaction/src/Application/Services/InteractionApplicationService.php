<?php

namespace RedJasmine\Interaction\Application\Services;

use RedJasmine\Interaction\Application\Services\Commands\InteractionCreateCommandHandler;
use RedJasmine\Support\Application\ApplicationService;

class InteractionApplicationService extends ApplicationService
{

    // 完成互动

    // 删除互动

    protected static $macros = [
        'create' => InteractionCreateCommandHandler::class
    ];


}