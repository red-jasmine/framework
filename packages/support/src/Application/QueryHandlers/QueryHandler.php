<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Foundation\Hook\HasHooks;
use RedJasmine\Support\Foundation\Service\AwareServiceAble;
use RedJasmine\Support\Foundation\Service\MacroAwareService;

abstract class QueryHandler
{

    use HasHooks;

    use AwareServiceAble;


    public function getRepository() : ReadRepositoryInterface
    {
        if (property_exists($this, 'repository')) {
            return $this->repository;
        } else {
            return $this->getService()->getRepository();
        }
    }
}
