<?php

namespace RedJasmine\Support\Application;

use RedJasmine\Support\Foundation\Service\BootTrait;
use RedJasmine\Support\Foundation\Service\MacroAwareService;
use RedJasmine\Support\Foundation\Service\PipelineTrait;

class QueryHandler implements MacroAwareService
{
    use BootTrait;

    use AwareServiceHelper;

    /**
     * 如何进行可配置化
     */
    use PipelineTrait;
}
