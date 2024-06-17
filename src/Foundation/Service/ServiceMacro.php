<?php

namespace RedJasmine\Support\Foundation\Service;

class ServiceMacro implements MacroAwareService, MacroAwareArguments
{

    public function __construct()
    {
    }

    use AwareServiceAble;


    use AwareArgumentsAble;
}
