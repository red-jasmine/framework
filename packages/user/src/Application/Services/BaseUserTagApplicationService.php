<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\User\Domain\Models\UserTag;
use RedJasmine\User\Domain\Repositories\UserTagReadRepositoryInterface;
use RedJasmine\User\Domain\Repositories\UserTagRepositoryInterface;
use RedJasmine\User\Domain\Transformers\UseTagTransformer;

abstract class BaseUserTagApplicationService extends ApplicationService
{
    public function tree(Query $query) : array
    {
        return $this->readRepository->tree($query);
    }

}