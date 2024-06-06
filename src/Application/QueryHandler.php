<?php

namespace RedJasmine\Support\Application;

use RedJasmine\Support\Foundation\Service\AwareArgumentsAble;
use RedJasmine\Support\Foundation\Service\AwareServiceAble;
use RedJasmine\Support\Foundation\Service\MacroAwareArguments;
use RedJasmine\Support\Foundation\Service\MacroAwareService;

/**
 * @property ApplicationQueryService $service
 * @method  ApplicationQueryService getService()
 */
class QueryHandler implements MacroAwareService, MacroAwareArguments
{


    use AwareServiceAble;


    use AwareArgumentsAble;
}
