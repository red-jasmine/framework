<?php

namespace RedJasmine\Support\Application;

use RedJasmine\Support\Foundation\Service\BootTrait;
use RedJasmine\Support\Foundation\Service\HasServiceContext;
use RedJasmine\Support\Foundation\Service\PipelineTrait;

class QueryHandler
{
    use BootTrait;

    use WithService;

    /**
     * 如何进行可配置化
     */
    use PipelineTrait;

    use HasServiceContext;
}
