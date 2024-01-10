<?php

namespace RedJasmine\Support\Foundation\Service;

use BadMethodCallException;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Traits\Macroable;

abstract class Service
{

    use HasActions;

    use WithUserService;

    use WithClientService;
}
