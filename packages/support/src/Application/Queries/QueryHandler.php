<?php

namespace RedJasmine\Support\Application\Queries;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Foundation\Hook\HasHooks;
use RedJasmine\Support\Foundation\Service\AwareServiceAble;

abstract class QueryHandler
{

    use HasHooks;
}
